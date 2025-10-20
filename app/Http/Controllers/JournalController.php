<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $query = Journal::with('details.account', 'creator');

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('transaction_date', [
                $request->start_date, 
                $request->end_date
            ]);
        }

        $journals = $query->orderBy('transaction_date', 'desc')->paginate(20);
        return view('journals.index', compact('journals'));
    }

    public function create()
    {
        $accounts = Account::where('is_active', true)->get();
        $journalNo = Journal::generateJournalNo();
        return view('journals.create', compact('accounts', 'journalNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'description' => 'required',
            'details' => 'required|array|min:2',
            'details.*.account_id' => 'required|exists:accounts,id',
            'details.*.description' => 'nullable',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.credit' => 'required|numeric|min:0',
        ]);

        // Validate balanced journal
        $totalDebit = collect($validated['details'])->sum('debit');
        $totalCredit = collect($validated['details'])->sum('credit');

        if ($totalDebit != $totalCredit) {
            return back()->withErrors(['error' => 'Journal not balanced! Debit and Credit must be equal.']);
        }

    $userId = Auth::id();

    DB::transaction(function () use ($validated, $totalDebit, $totalCredit, $userId) {
            $journal = Journal::create([
                'journal_no' => Journal::generateJournalNo(),
                'transaction_date' => $validated['transaction_date'],
                'description' => $validated['description'],
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'created_by' => $userId,
            ]);

            foreach ($validated['details'] as $detail) {
                if ($detail['debit'] > 0 || $detail['credit'] > 0) {
                    $journal->details()->create($detail);
                }
            }
        });

        return redirect()->route('journals.index')
            ->with('success', 'Journal entry created successfully');
    }

    public function show(Journal $journal)
    {
        $journal->load('details.account', 'creator');
        return view('journals.show', compact('journal'));
    }
}
