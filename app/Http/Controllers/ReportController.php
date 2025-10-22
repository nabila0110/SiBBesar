<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Journal;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Trial Balance (Neraca Saldo)
    public function trialBalance(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $accounts = Account::where('is_active', true)->get();
        
        $data = $accounts->map(function($account) use ($startDate, $endDate) {
            $balance = $account->getBalance($startDate, $endDate);
            return [
                'code' => $account->code,
                'name' => $account->name,
                'type' => $account->type,
                'debit' => $balance >= 0 ? $balance : 0,
                'credit' => $balance < 0 ? abs($balance) : 0,
            ];
        })->filter(function($item) {
            return $item['debit'] != 0 || $item['credit'] != 0;
        });

        return view('reports.trial-balance', compact('data', 'startDate', 'endDate'));
    }

    // Income Statement (Laba Rugi)
    public function incomeStatement(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $revenues = Account::where('type', 'revenue')->get();
        $expenses = Account::where('type', 'expense')->get();

        $revenueData = $revenues->map(function($acc) use ($startDate, $endDate) {
            // Revenue accounts typically have credit normal balance; getBalance returns debit-credit,
            // which will be negative when credits > debits. Use absolute value to present revenue as positive.
            $bal = $acc->getBalance($startDate, $endDate);
            return [
                'code' => $acc->code,
                'name' => $acc->name,
                'amount' => $bal < 0 ? abs($bal) : $bal,
            ];
        });

        $expenseData = $expenses->map(function($acc) use ($startDate, $endDate) {
            return [
                'code' => $acc->code,
                'name' => $acc->name,
                'amount' => $acc->getBalance($startDate, $endDate),
            ];
        });

        $totalRevenue = $revenueData->sum('amount');
        $totalExpense = $expenseData->sum('amount');
        $netIncome = $totalRevenue - $totalExpense;

        return view('reports.income-statement', compact(
            'revenueData', 'expenseData', 'totalRevenue', 
            'totalExpense', 'netIncome', 'startDate', 'endDate'
        ));
    }

    // Balance Sheet (Neraca)
    public function balanceSheet(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $assets = Account::where('type', 'asset')->get();
        $liabilities = Account::where('type', 'liability')->get();
        $equity = Account::where('type', 'equity')->get();

        $assetData = $assets->map(function($acc) use ($date) {
            return [
                'code' => $acc->code,
                'name' => $acc->name,
                'amount' => $acc->getBalance(null, $date),
            ];
        });

        $liabilityData = $liabilities->map(function($acc) use ($date) {
            return [
                'code' => $acc->code,
                'name' => $acc->name,
                'amount' => $acc->getBalance(null, $date),
            ];
        });

        $equityData = $equity->map(function($acc) use ($date) {
            return [
                'code' => $acc->code,
                'name' => $acc->name,
                'amount' => $acc->getBalance(null, $date),
            ];
        });

        $totalAssets = $assetData->sum('amount');
        $totalLiabilities = $liabilityData->sum('amount');
        $totalEquity = $equityData->sum('amount');

        return view('reports.balance-sheet', compact(
            'assetData', 'liabilityData', 'equityData',
            'totalAssets', 'totalLiabilities', 'totalEquity', 'date'
        ));
    }

    // General Ledger (Buku Besar)
    public function generalLedger(Request $request)
    {
        $accountId = $request->input('account_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $accounts = Account::where('is_active', true)->get();
        $ledgerData = null;

        if ($accountId) {
            $account = Account::findOrFail($accountId);
            
            $query = $account->journalDetails()
                ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
                ->select('journal_details.*', 'journals.transaction_date', 'journals.journal_no');

            if ($startDate) $query->where('journals.transaction_date', '>=', $startDate);
            if ($endDate) $query->where('journals.transaction_date', '<=', $endDate);

            $transactions = $query->orderBy('journals.transaction_date')->get();

            $balance = 0;
            $ledgerData = $transactions->map(function($detail) use (&$balance, $account) {
                if ($account->normal_balance === 'debit') {
                    $balance += $detail->debit - $detail->credit;
                } else {
                    $balance += $detail->credit - $detail->debit;
                }

                return [
                    'date' => $detail->transaction_date,
                    'journal_no' => $detail->journal_no,
                    'description' => $detail->description,
                    'debit' => $detail->debit,
                    'credit' => $detail->credit,
                    'balance' => $balance,
                ];
            });
        }

        return view('reports.general-ledger', compact(
            'accounts', 'ledgerData', 'accountId', 'startDate', 'endDate'
        ));
    }
}
