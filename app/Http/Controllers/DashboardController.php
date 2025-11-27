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
            // Count total journals (hanya jurnal utama: is_paired = false)
            $journalCount = Journal::where('is_paired', false)->count();
            
            // Count total accounts
            $accountCount = Account::where('is_active', true)->count();
            
            // Calculate HUTANG: Transaksi OUT + TIDAK_LUNAS (belum dibayar)
            $totalHutang = Journal::where('type', 'out')
                ->where('payment_status', 'tidak_lunas')
                ->where('is_paired', false)
                ->sum('final_total');
            
            // Calculate PIUTANG: Transaksi IN + TIDAK_LUNAS (belum diterima)
            $totalPiutang = Journal::where('type', 'in')
                ->where('payment_status', 'tidak_lunas')
                ->where('is_paired', false)
                ->sum('final_total');
            
            // Count hutang transactions (hanya jurnal utama)
            $countHutang = Journal::where('type', 'out')
                ->where('payment_status', 'tidak_lunas')
                ->where('is_paired', false)
                ->count();
            
            // Count piutang transactions (hanya jurnal utama)
            $countPiutang = Journal::where('type', 'in')
                ->where('payment_status', 'tidak_lunas')
                ->where('is_paired', false)
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

        // Get available years from journals
        $availableYears = Journal::selectRaw('YEAR(transaction_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        // Default to current year if no data
        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        }

        return view('dashboard', compact(
            'journalCount', 
            'accountCount', 
            'totalHutang', 
            'totalPiutang',
            'countHutang',
            'countPiutang',
            'availableYears'
        ));
    }

    /**
     * Get chart data for a specific year via AJAX
     */
    public function getChartData(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        // Get monthly data for IN (Piutang/Pemasukan) - hanya jurnal utama
        $monthlyIn = Journal::where('type', 'in')
            ->whereYear('transaction_date', $year)
            ->where('is_paired', false)
            ->selectRaw('MONTH(transaction_date) as month, SUM(final_total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
        
        // Get monthly data for OUT (Hutang/Pengeluaran) - hanya jurnal utama
        $monthlyOut = Journal::where('type', 'out')
            ->whereYear('transaction_date', $year)
            ->where('is_paired', false)
            ->selectRaw('MONTH(transaction_date) as month, SUM(final_total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
        
        // Fill all 12 months with 0 if no data
        $dataIn = [];
        $dataOut = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataIn[] = $monthlyIn[$i] ?? 0;
            $dataOut[] = $monthlyOut[$i] ?? 0;
        }
        
        return response()->json([
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Oct', 'Nov', 'Des'],
            'dataIn' => $dataIn,
            'dataOut' => $dataOut
        ]);
    }
}
