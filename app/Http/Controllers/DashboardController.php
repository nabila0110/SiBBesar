<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Count total journals
            $journalCount = Journal::count();
            
            // Count total accounts
            $accountCount = Account::where('is_active', true)->count();
            
            // Calculate HUTANG: OUT + TIDAK_LUNAS
            $totalHutang = Journal::where('type', 'out')
                ->where('payment_status', 'tidak_lunas')
                ->sum('final_total');
            
            // Calculate PIUTANG: IN + TIDAK_LUNAS
            $totalPiutang = Journal::where('type', 'in')
                ->where('payment_status', 'tidak_lunas')
                ->sum('final_total');
            
            // Count hutang transactions
            $countHutang = Journal::where('type', 'out')
                ->where('payment_status', 'tidak_lunas')
                ->count();
            
            // Count piutang transactions
            $countPiutang = Journal::where('type', 'in')
                ->where('payment_status', 'tidak_lunas')
                ->count();
                
        } catch (\Exception $e) {
            // If tables don't exist or DB not migrated, fall back to zeros
            $journalCount = 0;
            $accountCount = 0;
            $totalHutang = 0;
            $totalPiutang = 0;
            $countHutang = 0;
            $countPiutang = 0;
        }

        return view('dashboard', compact(
            'journalCount', 
            'accountCount', 
            'totalHutang', 
            'totalPiutang',
            'countHutang',
            'countPiutang'
        ));
    }
}
