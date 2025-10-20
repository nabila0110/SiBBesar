<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Journal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Calculate financial summary
        $cashAccount = Account::where('code', 'like', '1-1%')->get();
        $saldoKas = $cashAccount->sum(function($acc) {
            return $acc->getBalance();
        });

        $receivableAccount = Account::where('code', 'like', '1-2%')->get();
        $piutangUsaha = $receivableAccount->sum(function($acc) {
            return $acc->getBalance();
        });

        $payableAccount = Account::where('code', 'like', '2-1%')->get();
        $hutangUsaha = $payableAccount->sum(function($acc) {
            return $acc->getBalance();
        });

        $recentJournals = Journal::with('details.account')
            ->orderBy('transaction_date', 'desc')
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'saldoKas', 'piutangUsaha', 'hutangUsaha', 'recentJournals'
        ));
    }
}
