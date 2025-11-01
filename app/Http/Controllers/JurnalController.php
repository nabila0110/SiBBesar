<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JurnalController extends Controller
{
    /**
     * Display a listing of the journals.
     */
    public function index()
    {
        // show recent journals with pagination
        $journals = Journal::with('details.account')->orderBy('transaction_date', 'desc')->paginate(25);
        return view('jurnal.index', compact('journals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jurnal.create');
    }

    /**
     * Store a newly created journal.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'tanggal' => 'required|date',
            'bukti' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'akun' => 'nullable|string',
            'kode' => 'nullable|string',
            'debit' => 'nullable|numeric',
            'kredit' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $journalNo = $request->input('bukti') ?: Journal::generateJournalNo();
            $transactionDate = $request->input('tanggal');
            $description = $request->input('keterangan');

            $debit = (float) ($request->input('debit') ?: 0);
            $credit = (float) ($request->input('kredit') ?: 0);

            $journal = Journal::create([
                'journal_no' => $journalNo,
                'transaction_date' => $transactionDate,
                'description' => $description,
                'reference' => null,
                'total_debit' => $debit,
                'total_credit' => $credit,
                'status' => 'draft',
                'created_by' => Auth::id() ?? null,
            ]);

            // Find or create account
            $account = null;
            $akunInput = $request->input('akun');
            $kode = $request->input('kode');
            if ($kode) {
                $account = Account::where('code', $kode)->first();
            } elseif ($akunInput) {
                $account = Account::where('name', $akunInput)->first();
            }

            if (! $account && $akunInput) {
                // create placeholder account of type expense
                $code = 'AC' . strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', $akunInput), 0, 6)) . rand(10,99);
                $account = Account::create([
                    'code' => $code,
                    'name' => substr($akunInput,0,255),
                    'type' => 'expense',
                    'normal_balance' => 'debit',
                    'is_active' => true,
                    'balance_debit' => 0,
                    'balance_credit' => 0,
                ]);
            }

            $accountId = $account->id ?? null;


            // Create a journal detail (single line)
            $detail = JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $accountId,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'line_number' => 1,
            ]);

            // reload journal with details for response
            $journal->load('details.account');

            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Jurnal berhasil disimpan', 'data' => $journal], 201);
            }

            return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil disimpan');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $journal = Journal::with('details.account')->findOrFail($id);
        return view('jurnal.show', compact('journal'));
    }

    /**
     * Show the form for editing the specified journal.
     */
    public function edit($id)
    {
        $journal = Journal::with('details.account')->findOrFail($id);
        return view('jurnal.edit', compact('journal'));
    }

    /**
     * Update the specified journal.
     */
    public function update(Request $request, $id)
    {
        $journal = Journal::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'bukti' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $journal->transaction_date = $request->input('tanggal');
        $journal->journal_no = $request->input('bukti') ?: $journal->journal_no;
        $journal->description = $request->input('keterangan');
        $journal->save();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Jurnal berhasil diperbarui', 'data' => $journal]);
        }

        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil diperbarui');
    }

    /**
     * Remove the specified journal from storage.
     */
    public function destroy($id)
    {
        $journal = Journal::findOrFail($id);
        $journal->delete();
        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil dihapus');
    }
}