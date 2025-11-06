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
    public function index(Request $request)
    {
        // Query dasar sekarang ada di JournalDetail
        // Kita juga join ke tabel journals agar bisa sorting/filter berdasarkan tanggalnya
        $query = JournalDetail::with(['journal', 'account'])
            ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
            ->orderBy('journals.transaction_date', 'desc') // Urutkan berdasarkan tanggal jurnal
            ->orderBy('journal_details.id', 'asc'); // Lalu berdasarkan ID detail

        // Terapkan filter tanggal jika ada di request
        if ($request->has('dari_tanggal') && $request->input('dari_tanggal') != '') {
            $query->where('journals.transaction_date', '>=', $request->input('dari_tanggal'));
        }
        if ($request->has('sampai_tanggal') && $request->input('sampai_tanggal') != '') {
            $query->where('journals.transaction_date', '<=', $request->input('sampai_tanggal'));
        }

        // Ambil data - PENTING: kita select detailnya agar tidak bentrok nama kolom 'id'
        // Kita paginasi 25 DETAIL (baris), bukan 25 JURNAL
        $details = $query->select('journal_details.*')->paginate(25);

        // Jika request adalah AJAX (dari JavaScript fetch), kirim data JSON
        if ($request->wantsJson()) {
            return response()->json($details);
        }

        // Jika tidak, tampilkan halaman Blade
        // Untuk kompatibilitas dengan view, kita pass $journals tapi value-nya dari $details
        // Namun kita perlu restructure data agar view masih bisa pakai $journals->details
        return view('jurnal.index', compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::where('is_active', true)->orderBy('code')->get();
        return view('jurnal.create', compact('accounts'));
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
            'account_id' => 'required|exists:accounts,id',
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
            $accountId = $request->input('account_id');

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

            // Create a journal detail (single line) with selected account
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