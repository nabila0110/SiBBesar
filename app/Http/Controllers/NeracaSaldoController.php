<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\JournalDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class NeracaSaldoController extends Controller
{
    /**
     * Neraca Saldo Awal - Menampilkan saldo awal periode
     */
    public function awal(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', 1); // Januari sebagai default
        
        // Tanggal awal periode (1 Januari tahun terpilih)
        $periodeAwal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        
        // Ambil semua akun aktif
        $accounts = Account::where('is_active', true)
            ->orderBy('code')
            ->get();
        
        $dataNeraca = [];
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($accounts as $account) {
            // Hitung saldo awal (transaksi sebelum tanggal periode)
            $saldoAwal = JournalDetail::select(
                DB::raw('COALESCE(SUM(debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(credit), 0) as total_credit')
            )
            ->join('journals', 'journals.id', '=', 'journal_details.journal_id')
            ->where('journal_details.account_id', $account->id)
            ->where('journals.status', 'posted')
            ->where('journals.transaction_date', '<', $periodeAwal)
            ->first();

            $debit = $saldoAwal->total_debit ?? 0;
            $kredit = $saldoAwal->total_credit ?? 0;
            
            // Hitung saldo berdasarkan tipe akun
            if ($account->normal_balance === 'debit') {
                $saldo = $debit - $kredit;
                if ($saldo > 0) {
                    $debitAmount = $saldo;
                    $kreditAmount = 0;
                } else {
                    $debitAmount = 0;
                    $kreditAmount = abs($saldo);
                }
            } else {
                $saldo = $kredit - $debit;
                if ($saldo > 0) {
                    $debitAmount = 0;
                    $kreditAmount = $saldo;
                } else {
                    $debitAmount = abs($saldo);
                    $kreditAmount = 0;
                }
            }

            // Hanya tampilkan akun yang memiliki saldo
            if ($debitAmount != 0 || $kreditAmount != 0) {
                $dataNeraca[] = [
                    'akun' => '[' . $account->code . '] ' . $account->name,
                    'code' => $account->code,
                    'name' => $account->name,
                    'debit' => $debitAmount,
                    'kredit' => $kreditAmount,
                ];

                $totalDebit += $debitAmount;
                $totalKredit += $kreditAmount;
            }
        }

        return view('neraca-saldo-awal', compact('dataNeraca', 'tahun', 'bulan', 'totalDebit', 'totalKredit'));
    }

    /**
     * Neraca Saldo Akhir - Menampilkan saldo akhir periode
     */
    public function akhir(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('m'));
        
        // Tanggal akhir periode
        $periodeAkhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
        
        // Ambil semua akun aktif
        $accounts = Account::where('is_active', true)
            ->orderBy('code')
            ->get();
        
        $dataNeraca = [];
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($accounts as $account) {
            // Hitung saldo sampai akhir periode
            $saldo = JournalDetail::select(
                DB::raw('COALESCE(SUM(debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(credit), 0) as total_credit')
            )
            ->join('journals', 'journals.id', '=', 'journal_details.journal_id')
            ->where('journal_details.account_id', $account->id)
            ->where('journals.status', 'posted')
            ->where('journals.transaction_date', '<=', $periodeAkhir)
            ->first();

            $debit = $saldo->total_debit ?? 0;
            $kredit = $saldo->total_credit ?? 0;
            
            // Hitung saldo berdasarkan tipe akun
            if ($account->normal_balance === 'debit') {
                $saldoAkhir = $debit - $kredit;
                if ($saldoAkhir > 0) {
                    $debitAmount = $saldoAkhir;
                    $kreditAmount = 0;
                } else {
                    $debitAmount = 0;
                    $kreditAmount = abs($saldoAkhir);
                }
            } else {
                $saldoAkhir = $kredit - $debit;
                if ($saldoAkhir > 0) {
                    $debitAmount = 0;
                    $kreditAmount = $saldoAkhir;
                } else {
                    $debitAmount = abs($saldoAkhir);
                    $kreditAmount = 0;
                }
            }

            // Hanya tampilkan akun yang memiliki saldo
            if ($debitAmount != 0 || $kreditAmount != 0) {
                $dataNeraca[] = [
                    'akun' => '[' . $account->code . '] ' . $account->name,
                    'code' => $account->code,
                    'name' => $account->name,
                    'debit' => $debitAmount,
                    'kredit' => $kreditAmount,
                ];

                $totalDebit += $debitAmount;
                $totalKredit += $kreditAmount;
            }
        }

        return view('neraca-saldo-akhir', compact('dataNeraca', 'tahun', 'bulan', 'totalDebit', 'totalKredit'));
    }

    /**
     * Export Neraca Saldo Awal ke PDF
     */
    public function exportAwalPDF(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', 1);
        
        // Get data (reuse logic from awal method)
        $periodeAwal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $accounts = Account::where('is_active', true)->orderBy('code')->get();
        
        $dataNeraca = [];
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($accounts as $account) {
            $saldoAwal = JournalDetail::select(
                DB::raw('COALESCE(SUM(debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(credit), 0) as total_credit')
            )
            ->join('journals', 'journals.id', '=', 'journal_details.journal_id')
            ->where('journal_details.account_id', $account->id)
            ->where('journals.status', 'posted')
            ->where('journals.transaction_date', '<', $periodeAwal)
            ->first();

            $debit = $saldoAwal->total_debit ?? 0;
            $kredit = $saldoAwal->total_credit ?? 0;
            
            if ($account->normal_balance === 'debit') {
                $saldo = $debit - $kredit;
                $debitAmount = max(0, $saldo);
                $kreditAmount = max(0, -$saldo);
            } else {
                $saldo = $kredit - $debit;
                $kreditAmount = max(0, $saldo);
                $debitAmount = max(0, -$saldo);
            }

            if ($debitAmount != 0 || $kreditAmount != 0) {
                $dataNeraca[] = [
                    'akun' => '[' . $account->code . '] ' . $account->name,
                    'debit' => $debitAmount,
                    'kredit' => $kreditAmount,
                ];
                $totalDebit += $debitAmount;
                $totalKredit += $kreditAmount;
            }
        }

        $pdf = Pdf::loadView('neraca-saldo.pdf-awal', compact('dataNeraca', 'tahun', 'bulan', 'totalDebit', 'totalKredit'));
        return $pdf->download('Neraca_Saldo_Awal_' . $tahun . '.pdf');
    }

    /**
     * Export Neraca Saldo Akhir ke PDF
     */
    public function exportAkhirPDF(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('m'));
        
        // Get data (reuse logic from akhir method)
        $periodeAkhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
        $accounts = Account::where('is_active', true)->orderBy('code')->get();
        
        $dataNeraca = [];
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($accounts as $account) {
            $saldo = JournalDetail::select(
                DB::raw('COALESCE(SUM(debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(credit), 0) as total_credit')
            )
            ->join('journals', 'journals.id', '=', 'journal_details.journal_id')
            ->where('journal_details.account_id', $account->id)
            ->where('journals.status', 'posted')
            ->where('journals.transaction_date', '<=', $periodeAkhir)
            ->first();

            $debit = $saldo->total_debit ?? 0;
            $kredit = $saldo->total_credit ?? 0;
            
            if ($account->normal_balance === 'debit') {
                $saldoAkhir = $debit - $kredit;
                $debitAmount = max(0, $saldoAkhir);
                $kreditAmount = max(0, -$saldoAkhir);
            } else {
                $saldoAkhir = $kredit - $debit;
                $kreditAmount = max(0, $saldoAkhir);
                $debitAmount = max(0, -$saldoAkhir);
            }

            if ($debitAmount != 0 || $kreditAmount != 0) {
                $dataNeraca[] = [
                    'akun' => '[' . $account->code . '] ' . $account->name,
                    'debit' => $debitAmount,
                    'kredit' => $kreditAmount,
                ];
                $totalDebit += $debitAmount;
                $totalKredit += $kreditAmount;
            }
        }

        $pdf = Pdf::loadView('neraca-saldo.pdf-akhir', compact('dataNeraca', 'tahun', 'bulan', 'totalDebit', 'totalKredit'));
        return $pdf->download('Neraca_Saldo_Akhir_' . $tahun . '_' . $bulan . '.pdf');
    }
}