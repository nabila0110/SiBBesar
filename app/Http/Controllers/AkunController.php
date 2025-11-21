<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\AccountCategory;

class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $accounts = Account::with('category')->select(['code', 'name', 'group', 'type', 'account_category_id']);
            
            return response()->json([
                'data' => $accounts->get()->map(function($account) {
                    return [
                        'code' => $account->category->code . '-' . $account->code,
                        'name' => $account->name,
                        'group' => $account->group,
                        'type' => $account->type
                    ];
                })
            ]);
        }

        $accounts = Account::with('category')->orderBy('account_category_id')->orderBy('code')->get();
        $categories = AccountCategory::where('is_active', true)->orderBy('code')->get();

        return view('akun.index', compact('accounts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('akun.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_category_id' => 'required|exists:account_categories,id',
            'code' => 'required',
            'name' => 'required',
            'group' => 'required|in:Assets,Liabilities,Equity,Revenue,Expense',
            'type' => 'required',
            'expense_type' => 'nullable'
        ]);

        $account = Account::create([
            'account_category_id' => $validated['account_category_id'],
            'code' => $validated['code'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'group' => $validated['group'],
            'expense_type' => $validated['expense_type'] ?? null,
            'balance_debit' => 0,
            'balance_credit' => 0,
            'is_active' => true
        ]);

        if ($request->ajax()) {
            return response()->json($account);
        }
        
        return redirect()->route('akun.index')->with('success', 'Akun berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $account = Account::where('code', $id)->firstOrFail();
        return response()->json($account);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $account = Account::where('code', $id)->firstOrFail();
        return response()->json($account);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $account = Account::where('code', $id)->firstOrFail();

        $validated = $request->validate([
            'code' => 'required|unique:accounts,code,' . $account->id,
            'name' => 'required',
            'group' => 'nullable|in:Assets,Liabilities,Equity,Revenue,Expense',
            'type' => 'required',
            'expense_type' => 'nullable'
        ]);

        $account->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'group' => $validated['group'] ?? null,
            'expense_type' => $validated['expense_type'] ?? null
        ]);

        if ($request->ajax()) {
            return response()->json($account);
        }

        return redirect()->route('akun.index')->with('success', 'Akun berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $account = Account::where('code', $id)->firstOrFail();
        
        // Check if account can be deleted (no journal entries)
        if ($account->journalDetails()->exists()) {
            return response()->json(['message' => 'Akun tidak dapat dihapus karena sudah memiliki transaksi'], 422);
        }

        $account->delete();
        return response()->json(['message' => 'Akun berhasil dihapus']);
    }

    /**
     * Store opening balance for an account.
     */
    public function openBalance(Request $request)
    {
        $validated = $request->validate([
            'account_code' => 'required|exists:accounts,code',
            'type' => 'required|in:debit,kredit',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $account = Account::where('code', $validated['account_code'])->firstOrFail();
        // Create opening balance journal entry
        $journal = \App\Models\Journal::create([
            'journal_no' => \App\Models\Journal::generateJournalNo(),
            'transaction_date' => $validated['date'],
            'description' => $validated['description'] ?? 'Saldo Awal ' . $account->name,
            'reference' => 'SALDO-AWAL',
            'total_debit' => $validated['type'] === 'debit' ? $validated['amount'] : 0,
            'total_credit' => $validated['type'] === 'kredit' ? $validated['amount'] : 0,
            'created_by' => Auth::id()
        ]);

        // Create journal detail for the opening balance
        $journal->details()->create([
            'account_id' => $account->id,
            'description' => 'Saldo Awal',
            'debit' => $validated['type'] === 'debit' ? $validated['amount'] : 0,
            'credit' => $validated['type'] === 'kredit' ? $validated['amount'] : 0,
            'line_number' => 1
        ]);

        // Update account balance
        if ($validated['type'] === 'debit') {
            $account->increment('balance_debit', $validated['amount']);
        } else {
            $account->increment('balance_credit', $validated['amount']);
        }

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Saldo awal berhasil disimpan',
                'account' => $account->fresh()
            ]);
        }

        return redirect()->route('akun.index')->with('success', 'Saldo awal berhasil disimpan');
    }
}