<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\AccountCategory;

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
}