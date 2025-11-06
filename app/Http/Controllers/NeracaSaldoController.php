<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalDetail;
use App\Models\Receivable;
use App\Models\Payable;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NeracaSaldoController extends Controller
{
    /**
     * Show the Neraca Saldo Awal page.
     */
    public function awal()
    {
        $year = request()->query('year', date('Y'));
        $neracaData = $this->getNeracaData($year);
        
        return view('neraca-saldo-awal', [
            'neracaData' => $neracaData,
            'year' => $year
        ]);
    }

    /**
     * Show the Neraca (Balance Sheet) page - Main Report
     */
    public function index()
    {
        $year = request()->query('year', date('Y'));
        $period = request()->query('period', 'year'); // year, month, custom
        
        $neracaData = $this->getNeracaData($year, $period);
        
        return view('neraca', [
            'neracaData' => $neracaData,
            'year' => $year,
            'period' => $period
        ]);
    }

    /**
     * Get Balance Sheet Data organized by account type
     */
    private function getNeracaData($year, $period = 'year')
    {
        $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
        $endDate = Carbon::createFromDate($year, 12, 31)->endOfYear();

        if ($period === 'month') {
            $month = request()->query('month', date('m'));
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        }

        // Get all accounts grouped by type
        $assets = $this->getAccountsWithBalance('asset', $startDate, $endDate);
        $liabilities = $this->getAccountsWithBalance('liability', $startDate, $endDate);
        $equity = $this->getAccountsWithBalance('equity', $startDate, $endDate);

        // Calculate totals with receivables and payables
        $totalAssets = $assets->sum('balance') + $this->getTotalReceivables($startDate, $endDate);
        $totalLiabilities = $liabilities->sum('balance') + $this->getTotalPayables($startDate, $endDate);
        $totalEquity = $equity->sum('balance');

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'receivables' => $this->getReceivablesDetail($startDate, $endDate),
            'payables' => $this->getPayablesDetail($startDate, $endDate),
            'totalAssets' => $totalAssets,
            'totalLiabilities' => $totalLiabilities,
            'totalEquity' => $totalEquity,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
    }

    /**
     * Get accounts with calculated balance
     */
    private function getAccountsWithBalance($type, $startDate, $endDate)
    {
        return Account::where('type', $type)
            ->where('is_active', true)
            ->orderBy('code')
            ->get()
            ->map(function ($account) use ($startDate, $endDate) {
                $totals = JournalDetail::whereHas('journal', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('transaction_date', [$startDate, $endDate]);
                })
                ->where('account_id', $account->id)
                ->selectRaw('COALESCE(SUM(debit),0) as total_debit, COALESCE(SUM(credit),0) as total_credit')
                ->first();

                $debit = $totals->total_debit ?? 0;
                $credit = $totals->total_credit ?? 0;

                // Calculate balance based on normal balance
                if ($account->normal_balance === 'debit') {
                    $balance = $debit - $credit;
                } else {
                    $balance = $credit - $debit;
                }

                return [
                    'code' => $account->code,
                    'name' => $account->name,
                    'description' => $account->description,
                    'debit' => (float) $debit,
                    'credit' => (float) $credit,
                    'balance' => (float) $balance,
                    'type' => $account->type,
                ];
            });
    }

    /**
     * Get receivables detail
     */
    private function getReceivablesDetail($startDate, $endDate)
    {
        return Receivable::whereBetween('invoice_date', [$startDate, $endDate])
            ->with('account')
            ->orderBy('invoice_no')
            ->get();
    }

    /**
     * Get payables detail
     */
    private function getPayablesDetail($startDate, $endDate)
    {
        return Payable::whereBetween('invoice_date', [$startDate, $endDate])
            ->with('account')
            ->orderBy('invoice_no')
            ->get();
    }

    /**
     * Get total receivables
     */
    private function getTotalReceivables($startDate, $endDate)
    {
        return Receivable::whereBetween('invoice_date', [$startDate, $endDate])
            ->sum('remaining_amount') ?? 0;
    }

    /**
     * Get total payables
     */
    private function getTotalPayables($startDate, $endDate)
    {
        return Payable::whereBetween('invoice_date', [$startDate, $endDate])
            ->sum('remaining_amount') ?? 0;
    }

    /**
     * Return neraca saldo data (debit/credit sums) for a given year as JSON.
     * Frontend calls this to render the table dynamically.
     */
    public function dataAwal(\Illuminate\Http\Request $request)
    {
        $year = $request->query('year', date('Y'));
        $start = $year . '-01-01';
        $end = $year . '-12-31';

        // Collect accounts and compute sums from journal details joined with journals
        $accounts = Account::orderBy('code')->get();

        $rows = [];
        foreach ($accounts as $acct) {
            $totals = JournalDetail::join('journals', 'journal_details.journal_id', '=', 'journals.id')
                ->where('journal_details.account_id', $acct->id)
                ->whereBetween('journals.transaction_date', [$start, $end])
                ->selectRaw('COALESCE(SUM(journal_details.debit),0) as total_debit, COALESCE(SUM(journal_details.credit),0) as total_credit')
                ->first();

            $debit = $totals->total_debit ?? 0;
            $credit = $totals->total_credit ?? 0;

            $rows[] = [
                'code' => $acct->code,
                'name' => $acct->name,
                'debit' => (float) $debit,
                'credit' => (float) $credit,
            ];
        }

        return response()->json(['year' => $year, 'rows' => $rows]);
    }

    /**
     * Show the Neraca Saldo Akhir page.
     */
    public function akhir()
    {
        return view('neraca-saldo-akhir');
    }
}