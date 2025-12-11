<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies (card view)
     */
    public function index()
    {
        $companies = Company::withCount('journals')->orderBy('name')->get();
        return view('company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * Store a newly created company
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:companies,code',
            'name' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_active' => 'boolean'
        ], [
            'code.required' => 'Kode perusahaan harus diisi',
            'code.unique' => 'Kode perusahaan sudah digunakan',
            'name.required' => 'Nama perusahaan harus diisi',
            'logo.image' => 'File harus berupa gambar',
            'logo.max' => 'Ukuran logo maksimal 2MB',
            'email.email' => 'Format email tidak valid'
        ]);

        $data = $request->except('logo');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/companies'), $filename);
            $data['logo'] = $filename;
        }

        Company::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Perusahaan berhasil ditambahkan!'
        ]);
    }

    /**
     * Get company data for AJAX edit (returns JSON)
     */
    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return response()->json(['company' => $company]);
    }

    /**
     * Update the specified company
     */
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:20|unique:companies,code,' . $id,
            'name' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_active' => 'boolean'
        ], [
            'code.required' => 'Kode perusahaan harus diisi',
            'code.unique' => 'Kode perusahaan sudah digunakan',
            'name.required' => 'Nama perusahaan harus diisi',
            'logo.image' => 'File harus berupa gambar',
            'logo.max' => 'Ukuran logo maksimal 2MB',
            'email.email' => 'Format email tidak valid'
        ]);

        $data = $request->except('logo');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($company->logo && file_exists(public_path('images/companies/' . $company->logo))) {
                unlink(public_path('images/companies/' . $company->logo));
            }

            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/companies'), $filename);
            $data['logo'] = $filename;
        }

        $company->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Perusahaan berhasil diperbarui!'
        ]);
    }

    /**
     * Remove the specified company
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        // Check if company has journals
        if ($company->journals()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus perusahaan yang memiliki transaksi jurnal!'
            ], 400);
        }

        // Delete logo
        if ($company->logo && file_exists(public_path('images/companies/' . $company->logo))) {
            unlink(public_path('images/companies/' . $company->logo));
        }

        $company->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perusahaan berhasil dihapus!'
        ]);
    }

    /**
     * Show ledger (buku besar) for specific company
     */
    public function ledger($id, Request $request)
    {
        $company = Company::findOrFail($id);

        // Filter jurnal berdasarkan company dan tanggal
        $query = Journal::with(['account.category', 'creator'])
            ->where('company_id', $id)
            ->orderBy('account_id')
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc');

        // Filter tanggal
        if ($request->has('dari_tanggal') && $request->input('dari_tanggal') != '') {
            $query->where('transaction_date', '>=', $request->input('dari_tanggal'));
        }
        if ($request->has('sampai_tanggal') && $request->input('sampai_tanggal') != '') {
            $query->where('transaction_date', '<=', $request->input('sampai_tanggal'));
        }

        // Group by account_id
        $journals = $query->get()->groupBy('account_id');

        // Prepare data dengan pagination per account
        $groupedJournals = collect();
        foreach ($journals as $accountId => $accountJournals) {
            $perPage = 10;
            $currentPage = $request->input('page_' . $accountId, 1);
            $offset = ($currentPage - 1) * $perPage;
            
            $paginatedData = $accountJournals->slice($offset, $perPage);
            $total = $accountJournals->count();
            $lastPage = ceil($total / $perPage);

            $groupedJournals->put($accountId, [
                'data' => $paginatedData,
                'current_page' => $currentPage,
                'last_page' => $lastPage,
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $total),
                'total' => $total
            ]);
        }

        return view('company.ledger', compact('company', 'groupedJournals'));
    }

    /**
     * Export company ledger to PDF
     */
    public function exportLedgerPdf($id, Request $request)
    {
        $company = Company::findOrFail($id);

        $query = Journal::with(['account.category', 'creator'])
            ->where('company_id', $id)
            ->orderBy('account_id')
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc');

        // Filter tanggal
        if ($request->has('dari_tanggal') && $request->input('dari_tanggal') != '') {
            $query->where('transaction_date', '>=', $request->input('dari_tanggal'));
        }
        if ($request->has('sampai_tanggal') && $request->input('sampai_tanggal') != '') {
            $query->where('transaction_date', '<=', $request->input('sampai_tanggal'));
        }

        $journals = $query->get();
        $groupedJournals = $journals->groupBy('account_id');
        
        $data = [
            'company' => $company,
            'groupedJournals' => $groupedJournals,
            'dari_tanggal' => $request->input('dari_tanggal'),
            'sampai_tanggal' => $request->input('sampai_tanggal'),
            'tanggal_cetak' => Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY'),
            'user' => Auth::user()->name ?? 'Admin'
        ];

        $pdf = Pdf::loadView('company.ledger-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        $filename = 'Laporan_Buku_Besar_' . $company->code . '_' . date('Y-m-d_His') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export company ledger to Excel
     */
    public function exportLedgerExcel($id, Request $request)
    {
        $company = Company::findOrFail($id);

        $query = Journal::with(['account.category', 'creator'])
            ->where('company_id', $id)
            ->orderBy('account_id')
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc');

        // Filter tanggal
        if ($request->has('dari_tanggal') && $request->input('dari_tanggal') != '') {
            $query->where('transaction_date', '>=', $request->input('dari_tanggal'));
        }
        if ($request->has('sampai_tanggal') && $request->input('sampai_tanggal') != '') {
            $query->where('transaction_date', '<=', $request->input('sampai_tanggal'));
        }

        $journals = $query->get();
        $groupedJournals = $journals->groupBy('account_id');

        $filename = 'Laporan_Buku_Besar_' . $company->code . '_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new \App\Exports\CompanyLedgerExport($groupedJournals, $company), $filename);
    }
}
