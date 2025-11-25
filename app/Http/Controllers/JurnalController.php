<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
        $query = Journal::with(['account', 'creator'])
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
        return view('jurnal.create', compact('accounts'));
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
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

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

            $journal = Journal::create([
                'transaction_date' => $request->input('transaction_date'),
                'item' => $request->input('item'),
                'quantity' => $quantity,
                'satuan' => $request->input('satuan'),
                'price' => $price,
                'total' => $total,
                'tax' => $tax,
                'ppn_amount' => $ppn_amount,
                'final_total' => $final_total,
                'project' => $request->input('project'),
                'ket' => $request->input('ket'),
                'nota' => $request->input('nota'),
                'type' => $request->input('type'),
                'payment_status' => $request->input('payment_status'),
                'account_id' => $request->input('account_id'),
                'reference' => Journal::generateJournalNo(),
                'status' => 'posted',
                'created_by' => Auth::id(),
            ]);

            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Jurnal berhasil disimpan', 'data' => $journal], 201);
            }

            return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil disimpan');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
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
        return view('jurnal.edit', compact('journal', 'accounts'));
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
            'payment_status' => 'required|in:lunas,tidak_lunas',
            'account_id' => 'required|exists:accounts,id',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

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
                'project' => $request->input('project'),
                'nota' => $request->input('nota'),
                'type' => $request->input('type'),
                'payment_status' => $request->input('payment_status'),
                'account_id' => $request->input('account_id'),
                'updated_by' => Auth::id(),
            ]);

            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Jurnal berhasil diperbarui', 'data' => $journal]);
            }

            return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil diperbarui');
        } catch (\Exception $e) {
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
        try {
            $journal = Journal::findOrFail($id);
            $journal->delete();

            return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export Jurnal to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Journal::with(['account', 'creator'])
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
        $query = Journal::with(['account', 'creator'])
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