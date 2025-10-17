<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Receivable;
use App\Models\Payable;
use App\Models\Asset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $cashAccount = Account::where('type','asset')->where('code','like','1%')->first();
        $cashBalance = $cashAccount ? ($cashAccount->balance_debit - $cashAccount->balance_credit) : 0;

        $hutang = Payable::sum('remaining_amount');
        $piutang = Receivable::sum('remaining_amount');

        $journalCount = Journal::count();

        return view('dashboard', [
            'cashBalance' => $cashBalance,
            'hutang' => $hutang,
            'piutang' => $piutang,
            'journalCount' => $journalCount,
        ]);
    }
}
