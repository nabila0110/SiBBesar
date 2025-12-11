<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class JurnalController extends Controller
{
    /**
     * Display a listing of the journals.
     */
    public function index(Request $request)
    {
        // Hanya tampilkan jurnal utama (is_paired = false)
        $query = Journal::with(['account', 'creator', 'pairedJournal'])
            ->where('is_paired', false)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc');

        // Filter tanggal
        if ($request->has('dari_tanggal') && $request->input('dari_tanggal') != '') {
            $query->where('transaction_date', '>=', $request->input('dari_tanggal'));
        }
        if ($request->has('sampai_tanggal') && $request->input('sampai_tanggal') != '') {
            $query->where('transaction_date', '<=', $request->input('sampai_tanggal'));
        }

        $journals = $query->paginate(6);

        if ($request->wantsJson()) {
            return response()->json($journals);
        }

        return view('jurnal.index', compact('journals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::with('category')->where('is_active', true)->orderBy('account_category_id')->orderBy('code')->get();
        $companies = \App\Models\Company::where('is_active', true)->orderBy('name')->get();
        return view('jurnal.create', compact('accounts', 'companies'));
    }

    /**
     * Store a newly created journal.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_date' => 'required|date',
            'item' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'satuan' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'tax' => 'nullable|boolean',
            'project' => 'nullable|string|max:255',
            'ket' => 'nullable|string|max:255',
            'nota' => 'required|string|max:255',
            'type' => 'required|in:in,out',
            'payment_status' => 'required|in:lunas,tidak_lunas',
            'account_id' => 'required|exists:accounts,id',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $quantity = (float) $request->input('quantity', 1);
            $price = (float) $request->input('price', 0);
            $tax = $request->input('tax', false);

            // Calculate total = price * quantity
            $total = $quantity * $price;

            // Calculate PPN (11% if tax checkbox is checked)
            $ppn_amount = $tax ? ($total * 0.11) : 0;

            // Calculate final total
            $final_total = $total + $ppn_amount;

            $type = $request->input('type');
            $paymentStatus = $request->input('payment_status');

            // JURNAL 1: Akun yang dipilih user
            $journal1 = Journal::create([
                'transaction_date' => $request->input('transaction_date'),
                'item' => $request->input('item'),
                'quantity' => $quantity,
                'satuan' => $request->input('satuan'),
                'price' => $price,
                'total' => $total,
                'tax' => $tax,
                'ppn_amount' => $ppn_amount,
                'final_total' => $final_total,
                'debit' => $type === 'out' ? $final_total : 0,
                'kredit' => $type === 'in' ? $final_total : 0,
                'is_paired' => false, // Ini jurnal utama
                'project' => $request->input('project'),
                'ket' => $request->input('ket'),
                'nota' => $request->input('nota'),
                'type' => $type,
                'payment_status' => $paymentStatus,
                'account_id' => $request->input('account_id'),
                'company_id' => $request->input('company_id'),
                'reference' => Journal::generateJournalNo(),
                'status' => 'posted',
                'created_by' => Auth::id(),
            ]);

            // JURNAL 2: Akun pasangan (otomatis berdasarkan IN/OUT + Status)
            $pairedAccountId = $this->getPairedAccount($type, $paymentStatus);
            
            $journal2 = Journal::create([
                'transaction_date' => $request->input('transaction_date'),
                'item' => $request->input('item') . ' (Pasangan)',
                'quantity' => $quantity,
                'satuan' => $request->input('satuan'),
                'price' => $price,
                'total' => $total,
                'tax' => $tax,
                'ppn_amount' => $ppn_amount,
                'final_total' => $final_total,
                'debit' => $type === 'in' ? $final_total : 0, // Kebalikan dari journal1
                'kredit' => $type === 'out' ? $final_total : 0, // Kebalikan dari journal1
                'is_paired' => true, // Ini jurnal pasangan
                'paired_journal_id' => $journal1->id,
                'project' => $request->input('project'),
                'ket' => $request->input('ket'),
                'nota' => $request->input('nota'),
                'type' => $type,
                'payment_status' => $paymentStatus,
                'account_id' => $pairedAccountId,
                'company_id' => $request->input('company_id'),
                'reference' => $journal1->reference, // Reference sama
                'status' => 'posted',
                'created_by' => Auth::id(),
            ]);

            // Update journal1 dengan paired_journal_id
            $journal1->update(['paired_journal_id' => $journal2->id]);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success', 
                    'message' => 'Jurnal berhasil disimpan (Double Entry)', 
                    'data' => ['main' => $journal1, 'paired' => $journal2]
                ], 201);
            }

            return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Helper: Tentukan akun pasangan berdasarkan IN/OUT dan Status Pembayaran
     */
    private function getPairedAccount($type, $paymentStatus)
    {
        if ($type === 'in') {
            // IN + LUNAS → Kas (1-1100)
            // IN + TIDAK LUNAS → Piutang (1-1300)
            if ($paymentStatus === 'lunas') {
                // Cari akun Kas - coba beberapa cara
                $account = Account::with('category')
                    ->where(function($q) {
                        $q->where('name', 'like', '%kas%')
                          ->orWhere('code', '1100');
                    })
                    ->first();
            } else {
                // Cari akun Piutang
                $account = Account::with('category')
                    ->where(function($q) {
                        $q->where('name', 'like', '%piutang%')
                          ->orWhere('code', '1300');
                    })
                    ->first();
            }
        } else {
            // OUT + LUNAS → Kas (1-1100)
            // OUT + TIDAK LUNAS → Hutang (2-1100)
            if ($paymentStatus === 'lunas') {
                // Cari akun Kas
                $account = Account::with('category')
                    ->where(function($q) {
                        $q->where('name', 'like', '%kas%')
                          ->orWhere('code', '1100');
                    })
                    ->first();
            } else {
                // Cari akun Hutang
                $account = Account::with('category')
                    ->where(function($q) {
                        $q->where('name', 'like', '%hutang%')
                          ->orWhere('code', '1100');
                    })
                    ->first();
            }
        }

        // Fallback: Jika tidak ditemukan, gunakan akun pertama yang aktif
        if (!$account) {
            $account = Account::where('is_active', true)->first();
            if (!$account) {
                throw new \Exception('Tidak ada akun yang tersedia. Silakan tambahkan akun terlebih dahulu.');
            }
        }

        return $account->id;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $journal = Journal::with('account', 'creator')->findOrFail($id);
        return view('jurnal.show', compact('journal'));
    }

    /**
     * Show the form for editing the specified journal.
     */
    public function edit($id)
    {
        $journal = Journal::with('account')->findOrFail($id);
        $accounts = Account::with('category')->where('is_active', true)->orderBy('account_category_id')->orderBy('code')->get();
        $companies = \App\Models\Company::where('is_active', true)->orderBy('name')->get();
        return view('jurnal.edit', compact('journal', 'accounts', 'companies'));
    }

    /**
     * Update the specified journal.
     */
    public function update(Request $request, $id)
    {
        $journal = Journal::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'transaction_date' => 'required|date',
            'item' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'satuan' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'tax' => 'nullable|boolean',
            'project' => 'nullable|string|max:255',
            'nota' => 'required|string|max:255',
            'type' => 'required|in:in,out',
            'company_id' => 'nullable|exists:companies,id',
            'payment_status' => 'required|in:lunas,tidak_lunas',
            'account_id' => 'required|exists:accounts,id',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $quantity = (float) $request->input('quantity', 1);
            $price = (float) $request->input('price', 0);
            $tax = $request->input('tax', false);

            // Calculate total = price * quantity
            $total = $quantity * $price;

            // Calculate PPN (11% if tax checkbox is checked)
            $ppn_amount = $tax ? ($total * 0.11) : 0;

            // Calculate final total
            $final_total = $total + $ppn_amount;

            $type = $request->input('type');
            $paymentStatus = $request->input('payment_status');

            // Update JURNAL 1 (Jurnal Utama)
            $journal->update([
                'transaction_date' => $request->input('transaction_date'),
                'item' => $request->input('item'),
                'quantity' => $quantity,
                'satuan' => $request->input('satuan'),
                'price' => $price,
                'total' => $total,
                'tax' => $tax,
                'ppn_amount' => $ppn_amount,
                'final_total' => $final_total,
                'debit' => $type === 'out' ? $final_total : 0,
                'kredit' => $type === 'in' ? $final_total : 0,
                'project' => $request->input('project'),
                'nota' => $request->input('nota'),
                'type' => $type,
                'payment_status' => $paymentStatus,
                'account_id' => $request->input('account_id'),
                'company_id' => $request->input('company_id'),
                'updated_by' => Auth::id(),
            ]);

            // Update JURNAL 2 (Jurnal Pasangan) jika ada
            if ($journal->paired_journal_id) {
                $pairedJournal = Journal::find($journal->paired_journal_id);
                if ($pairedJournal) {
                    $pairedAccountId = $this->getPairedAccount($type, $paymentStatus);
                    
                    $pairedJournal->update([
                        'transaction_date' => $request->input('transaction_date'),
                        'item' => $request->input('item') . ' (Pasangan)',
                        'quantity' => $quantity,
                        'satuan' => $request->input('satuan'),
                        'price' => $price,
                        'total' => $total,
                        'tax' => $tax,
                        'ppn_amount' => $ppn_amount,
                        'final_total' => $final_total,
                        'debit' => $type === 'in' ? $final_total : 0,
                        'kredit' => $type === 'out' ? $final_total : 0,
                        'project' => $request->input('project'),
                        'nota' => $request->input('nota'),
                        'type' => $type,
                        'payment_status' => $paymentStatus,
                        'account_id' => $pairedAccountId,
                        'company_id' => $request->input('company_id'),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Jurnal berhasil diperbarui (Double Entry)', 'data' => $journal]);
            }

            return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified journal from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $journal = Journal::findOrFail($id);
            
            // Hapus juga jurnal pasangannya
            if ($journal->paired_journal_id) {
                $pairedJournal = Journal::find($journal->paired_journal_id);
                if ($pairedJournal) {
                    $pairedJournal->delete();
                }
            }
            
            $journal->delete();

            DB::commit();
            return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export Jurnal to PDF
     */
    public function exportPdf(Request $request)
    {
        // Hanya ambil jurnal utama (is_paired = false)
        $query = Journal::with(['account', 'creator'])
            ->where('is_paired', false)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc');

        // Filter tanggal
        if ($request->has('dari_tanggal') && $request->input('dari_tanggal') != '') {
            $query->where('transaction_date', '>=', $request->input('dari_tanggal'));
        }
        if ($request->has('sampai_tanggal') && $request->input('sampai_tanggal') != '') {
            $query->where('transaction_date', '<=', $request->input('sampai_tanggal'));
        }

        $journals = $query->get();
        
        $data = [
            'journals' => $journals,
            'dari_tanggal' => $request->input('dari_tanggal'),
            'sampai_tanggal' => $request->input('sampai_tanggal'),
            'tanggal_cetak' => Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY'),
            'user' => Auth::user()->name ?? 'Admin'
        ];

        $pdf = Pdf::loadView('jurnal.pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        $filename = 'Laporan_Jurnal_' . date('Y-m-d_His') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export Jurnal to Excel
     */
    public function exportExcel(Request $request)
    {
        // Hanya ambil jurnal utama (is_paired = false)
        $query = Journal::with(['account', 'creator'])
            ->where('is_paired', false)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc');

        // Filter tanggal
        if ($request->has('dari_tanggal') && $request->input('dari_tanggal') != '') {
            $query->where('transaction_date', '>=', $request->input('dari_tanggal'));
        }
        if ($request->has('sampai_tanggal') && $request->input('sampai_tanggal') != '') {
            $query->where('transaction_date', '<=', $request->input('sampai_tanggal'));
        }

        $journals = $query->get();

        $filename = 'Laporan_Jurnal_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new \App\Exports\JurnalExport($journals), $filename);
    }
}