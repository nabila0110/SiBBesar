<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BukuBesarController extends Controller
{
    /**
     * Display buku besar view with filtered data
     */
    public function index(Request $request)
    {
        $periode_from = $request->get('dari');
        $periode_to = $request->get('sampai');

        // If no date range specified, default to current month
        if (!$periode_from && !$periode_to) {
            $periode_from = Carbon::now()->startOfMonth()->format('Y-m-d');
            $periode_to = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        // Get active accounts that have transactions
        $accounts = Account::where('is_active', true)
            ->whereHas('journalDetails', function($q) use ($periode_from, $periode_to) {
                $q->whereHas('journal', function($j) use ($periode_from, $periode_to) {
                    if ($periode_from) {
                        $j->whereDate('transaction_date', '>=', $periode_from);
                    }
                    if ($periode_to) {
                        $j->whereDate('transaction_date', '<=', $periode_to);
                    }
                });
            })
            ->orderBy('code')
            ->get();
            
        $data = [];

        foreach ($accounts as $account) {
            // Get transactions for this account
            $query = JournalDetail::select(
                'journals.transaction_date as tanggal',
                'journals.description as transaksi',
                'journals.journal_no as nomor',
                'journal_details.debit',
                'journal_details.credit',
                'journal_details.description as detail'
            )
            ->join('journals', 'journals.id', '=', 'journal_details.journal_id')
            ->where('journal_details.account_id', $account->id)
            ->where('journals.status', 'posted')  // Only include posted journals
            ->when($periode_from, function($q) use ($periode_from) {
                return $q->whereDate('journals.transaction_date', '>=', $periode_from);
            })
            ->when($periode_to, function($q) use ($periode_to) {
                return $q->whereDate('journals.transaction_date', '<=', $periode_to);
            })
            ->orderBy('journals.transaction_date')
            ->orderBy('journals.id');

            $entries = $query->get();

            // Only include accounts that have transactions in the period
            if ($entries->isNotEmpty()) {
                // Calculate running balance
                $saldo = 0;
                $rows = [];

                // Get opening balance (sum of all transactions before start date)
                if ($periode_from) {
                    $openingBalance = JournalDetail::select(
                        DB::raw('SUM(debit) - SUM(credit) as balance')
                    )
                    ->join('journals', 'journals.id', '=', 'journal_details.journal_id')
                    ->where('journal_details.account_id', $account->id)
                    ->where('journals.status', 'posted')
                    ->where('journals.transaction_date', '<', $periode_from)
                    ->first();

                    // Adjust opening balance based on normal balance
                    $saldo = $openingBalance->balance ?? 0;
                    if ($account->normal_balance === 'credit') {
                        $saldo *= -1; // Invert for credit normal accounts
                    }

                    // Add opening balance row if there was activity before period
                    if ($saldo != 0) {
                        $rows[] = [
                            'tanggal' => Carbon::parse($periode_from)->format('d/m/Y'),
                            'nomor' => '-',
                            'transaksi' => 'Saldo Awal',
                            'detail' => '',
                            'debit' => $saldo > 0 ? abs($saldo) : 0,
                            'kredit' => $saldo < 0 ? abs($saldo) : 0,
                            'saldo' => $saldo
                        ];
                    }
                }

                // Add all transactions within period
                foreach ($entries as $entry) {
                    if ($account->normal_balance === 'credit') {
                        // For credit normal accounts, credit increases balance
                        $saldo += ($entry->credit - $entry->debit);
                    } else {
                        // For debit normal accounts, debit increases balance
                        $saldo += ($entry->debit - $entry->credit);
                    }

                    $rows[] = [
                        'tanggal' => Carbon::parse($entry->tanggal)->format('d/m/Y'),
                        'nomor' => $entry->nomor,
                        'transaksi' => $entry->transaksi,
                        'detail' => $entry->detail,
                        'debit' => $entry->debit,
                        'kredit' => $entry->credit,
                        'saldo' => $saldo
                    ];
                }

                $data[] = [
                    'account' => $account,
                    'rows' => $rows
                ];
            }
        }

        // PERBAIKAN: Ganti 'buku-besar' menjadi 'buku-besar.index'
        return view('buku-besar.index', compact('data', 'periode_from', 'periode_to'));
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