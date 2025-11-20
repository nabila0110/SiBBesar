<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Journal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BukuBesarController extends Controller
{
    /**
     * Display buku besar view with grouped journal data by account
     */
    public function index(Request $request)
    {
        $query = Journal::with(['account', 'creator'])
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

        if ($request->wantsJson()) {
            return response()->json($journals);
        }

        return view('buku-besar.index', compact('journals'));
    }

    /**
     * Export buku besar data to PDF (optional - can be implemented if needed)
     */
    public function exportPDF(Request $request)
    {
        // PDF export logic can be implemented here if needed
        // For now, we're handling PDF generation in frontend using jsPDF
    }
}