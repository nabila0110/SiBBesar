<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\JournalDetail;

class LaporanKeuanganController extends Controller
{
    /**
     * Show balance sheet (Laporan Posisi Keuangan) with live data from database.
     * Accepts optional year query param (currently only displayed, balances use cached values).
     */
    public function posisi(Request $request)
    {
        $year = $request->get('year', date('Y'));

        // Load active categories and their accounts grouped by type
        $assetCategories = AccountCategory::where('type', 'asset')
            ->with(['accounts' => function ($q) { $q->where('is_active', true)->orderBy('code'); }])
            ->orderBy('code')
            ->get();

        $liabilityCategories = AccountCategory::where('type', 'liability')
            ->with(['accounts' => function ($q) { $q->where('is_active', true)->orderBy('code'); }])
            ->orderBy('code')
            ->get();

        $equityCategories = AccountCategory::where('type', 'equity')
            ->with(['accounts' => function ($q) { $q->where('is_active', true)->orderBy('code'); }])
            ->orderBy('code')
            ->get();

        // Helper to prepare a structured array ready for the view
        // If $start/$end provided, call getBalance with range so balances reflect that period.
        $prepare = function ($categories, $start = null, $end = null) {
            $out = [];
            $sectionTotal = 0;
            foreach ($categories as $cat) {
                $items = [];
                $catTotal = 0;
                foreach ($cat->accounts as $acct) {
                    $bal = $acct->getBalance($start, $end);
                    $items[] = [
                        'code' => $acct->code,
                        'name' => $acct->name,
                        'balance' => $bal,
                    ];
                    $catTotal += $bal;
                }
                $out[] = [
                    'category' => $cat,
                    'items' => $items,
                    'total' => $catTotal,
                ];
                $sectionTotal += $catTotal;
            }
            return ['groups' => $out, 'total' => $sectionTotal];
        };

    // Build start/end for the selected year (balances as of 31 Dec of that year)
    $start = $year . '-01-01';
    $end = $year . '-12-31';

    $assets = $prepare($assetCategories, $start, $end);
    $liabilities = $prepare($liabilityCategories, $start, $end);
    $equities = $prepare($equityCategories, $start, $end);

        return view('laporan-posisi-keuangan', compact('year', 'assets', 'liabilities', 'equities'));
    }

    public function labaRugi()
    {
        // allow year via query param
        $year = request()->get('year', date('Y'));
        $start = $year . '-01-01';
        $end = $year . '-12-31';

        // load revenue and expense categories with accounts
        $revenueCategories = AccountCategory::where('type', 'revenue')
            ->with(['accounts' => function ($q) { $q->where('is_active', true)->orderBy('code'); }])
            ->orderBy('code')
            ->get();

        $expenseCategories = AccountCategory::where('type', 'expense')
            ->with(['accounts' => function ($q) { $q->where('is_active', true)->orderBy('code'); }])
            ->orderBy('code')
            ->get();

        $prepare = function ($categories, $start = null, $end = null) {
            $out = [];
            $total = 0;
            foreach ($categories as $cat) {
                $items = [];
                $catTotal = 0;
                foreach ($cat->accounts as $acct) {
                    $bal = $acct->getBalance($start, $end);
                    $items[] = [
                        'code' => $acct->code,
                        'name' => $acct->name,
                        'balance' => $bal,
                    ];
                    $catTotal += $bal;
                }
                $out[] = ['category' => $cat, 'items' => $items, 'total' => $catTotal];
                $total += $catTotal;
            }
            return ['groups' => $out, 'total' => $total];
        };

        $revenues = $prepare($revenueCategories, $start, $end);
        $expenses = $prepare($expenseCategories, $start, $end);

        return view('laporan-laba-rugi', compact('year', 'revenues', 'expenses'));
    }

    /**
     * Show detailed transaction report
     */
    public function transaksi(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $start = $year . '-01-01';
        $end = $year . '-12-31';

        // Get all transactions
        $transactions = JournalDetail::whereHas('journal', function ($query) use ($start, $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        })
        ->with(['journal', 'account'])
        ->orderBy('journal_id')
        ->paginate(50);

        // Calculate totals
        $totalDebit = JournalDetail::whereHas('journal', function ($query) use ($start, $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        })->sum('debit');

        $totalCredit = JournalDetail::whereHas('journal', function ($query) use ($start, $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        })->sum('credit');

        // Account Summary
        $accountSummary = JournalDetail::whereHas('journal', function ($query) use ($start, $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        })
        ->join('accounts', 'journal_details.account_id', '=', 'accounts.id')
        ->groupBy('journal_details.account_id', 'accounts.code', 'accounts.name')
        ->selectRaw('
            journal_details.account_id,
            accounts.code as account_code,
            accounts.name as account_name,
            COUNT(*) as count,
            SUM(journal_details.debit) as debit,
            SUM(journal_details.credit) as credit
        ')
        ->orderBy('accounts.code')
        ->get();

        // Type Summary
        $typeSummary = \App\Models\JournalDetail::whereHas('journal', function ($query) use ($start, $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        })
        ->join('accounts', 'journal_details.account_id', '=', 'accounts.id')
        ->groupBy('accounts.type')
        ->selectRaw('
            accounts.type,
            COUNT(*) as count,
            SUM(journal_details.debit) as debit,
            SUM(journal_details.credit) as credit
        ')
        ->pluck('debit', 'credit', 'count')
        ->get()
        ->groupBy(function ($item) {
            return $item->type ?? 'other';
        })
        ->map(function ($group) {
            $first = $group->first();
            return [
                'debit' => $first->debit ?? 0,
                'credit' => $first->credit ?? 0,
                'count' => $first->count ?? 0,
            ];
        });

        // Simplified type summary by raw query
        $typeSummaryRaw = DB::table('journal_details as jd')
            ->join('journals as j', 'jd.journal_id', '=', 'j.id')
            ->join('accounts as a', 'jd.account_id', '=', 'a.id')
            ->whereBetween('j.transaction_date', [$start, $end])
            ->groupBy('a.type')
            ->selectRaw('
                a.type,
                COUNT(*) as count,
                SUM(jd.debit) as debit,
                SUM(jd.credit) as credit
            ')
            ->get()
            ->keyBy('type');

        $startDate = \Carbon\Carbon::createFromDate($year, 1, 1);
        $endDate = \Carbon\Carbon::createFromDate($year, 12, 31);

        return view('laporan-transaksi', compact(
            'transactions',
            'totalDebit',
            'totalCredit',
            'accountSummary',
            'typeSummaryRaw',
            'year',
            'startDate',
            'endDate'
        ));
    }
}