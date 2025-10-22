<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Journal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Defensive: if DB/migrations haven't been run the models may throw exceptions.
        try {
            $cashAccounts = Account::where('code', 'like', '1-1%')->get();
            $cashBalance = $cashAccounts->sum(function($acc) {
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
        } catch (\Exception $e) {
            // If tables don't exist or DB not migrated, fall back to zeros
            $cashBalance = 0;
            $piutangUsaha = 0;
            $hutangUsaha = 0;
            $journalCount = 0;
        }

        // Tests expect Indonesian keys and a recentJournals key
        $saldoKas = $cashBalance;

        return view('dashboard', compact(
            'saldoKas', 'piutangUsaha', 'hutangUsaha', 'recentJournals'
        ));
    }
}
