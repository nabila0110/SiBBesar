<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Journal;
use App\Models\Payable;
use App\Models\Receivable;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Defensive: if DB/migrations haven't been run the models may throw exceptions.
        try {
            // Get total cash/money from all asset accounts
            $allAssetAccounts = Account::where('type', 'asset')->get();
            $totalSaldoKas = $allAssetAccounts->sum(function($acc) {
                return $acc->getBalance() ?? 0;
            });

            $receivableAccounts = Account::where('code', 'like', '1-2%')->get();
            $piutangUsaha = $receivableAccounts->sum(function($acc) {
                return $acc->getBalance() ?? 0;
            });

            $payableAccounts = Account::where('code', 'like', '2-1%')->get();
            $hutangUsaha = $payableAccounts->sum(function($acc) {
                return $acc->getBalance() ?? 0;
            });

            $journalCount = Journal::count();
            $recentJournals = Journal::latest()->limit(5)->get();
            
            // Get total receivables and payables from database
            $totalRecievables = Receivable::sum('remaining_amount') ?? 0;
            $totalPayables = Payable::sum('remaining_amount') ?? 0;
            $accountCount = Account::count();
        } catch (\Exception $e) {
            // If tables don't exist or DB not migrated, fall back to zeros
            $totalSaldoKas = 0;
            $piutangUsaha = 0;
            $hutangUsaha = 0;
            $journalCount = 0;
            $totalRecievables = 0;
            $totalPayables = 0;
            $accountCount = 0;
        }

        return view('dashboard', compact(
            'totalSaldoKas', 'piutangUsaha', 'hutangUsaha', 'recentJournals', 
            'journalCount', 'totalRecievables', 'totalPayables', 'accountCount'
        ));
    }
}

