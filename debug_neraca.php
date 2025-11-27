<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Account;
use App\Models\Journal;

$start = '2025-01-01';
$end = '2025-11-27';

echo "=== CEK NERACA BALANCE ===\n\n";

// Cek Aktiva
echo "AKTIVA:\n";
$assets = Account::where('type', 'asset')->where('is_active', true)->get();
$totalAktiva = 0;
foreach($assets as $acc) {
    $debit = Journal::where('account_id', $acc->id)
        ->whereBetween('transaction_date', [$start, $end])
        ->where('is_paired', false)
        ->sum('debit');
    $kredit = Journal::where('account_id', $acc->id)
        ->whereBetween('transaction_date', [$start, $end])
        ->where('is_paired', false)
        ->sum('kredit');
    $balance = $debit - $kredit;
    echo "{$acc->code} - {$acc->name}: " . number_format($balance, 0, ',', '.') . "\n";
    $totalAktiva += $balance;
}
echo "TOTAL AKTIVA: " . number_format($totalAktiva, 0, ',', '.') . "\n\n";

// Cek Kewajiban
echo "KEWAJIBAN:\n";
$liabilities = Account::where('type', 'liability')->where('is_active', true)->get();
$totalKewajiban = 0;
foreach($liabilities as $acc) {
    $debit = Journal::where('account_id', $acc->id)
        ->whereBetween('transaction_date', [$start, $end])
        ->where('is_paired', false)
        ->sum('debit');
    $kredit = Journal::where('account_id', $acc->id)
        ->whereBetween('transaction_date', [$start, $end])
        ->where('is_paired', false)
        ->sum('kredit');
    $balance = $kredit - $debit; // Liability normal kredit
    echo "{$acc->code} - {$acc->name}: " . number_format($balance, 0, ',', '.') . "\n";
    $totalKewajiban += $balance;
}
echo "TOTAL KEWAJIBAN: " . number_format($totalKewajiban, 0, ',', '.') . "\n\n";

// Cek Ekuitas
echo "EKUITAS:\n";
$equities = Account::where('type', 'equity')->where('is_active', true)->get();
$totalEkuitas = 0;
foreach($equities as $acc) {
    $debit = Journal::where('account_id', $acc->id)
        ->whereBetween('transaction_date', [$start, $end])
        ->where('is_paired', false)
        ->sum('debit');
    $kredit = Journal::where('account_id', $acc->id)
        ->whereBetween('transaction_date', [$start, $end])
        ->where('is_paired', false)
        ->sum('kredit');
    $balance = $kredit - $debit; // Equity normal kredit
    
    // Jika Laba Ditahan, tambahkan net income
    if (stripos($acc->name, 'laba') !== false || stripos($acc->name, 'rugi') !== false) {
        $revenues = Account::where('type', 'revenue')->where('is_active', true)->pluck('id');
        $totalRevenue = Journal::whereIn('account_id', $revenues)
            ->whereBetween('transaction_date', [$start, $end])
            ->where('is_paired', false)
            ->sum('kredit') - Journal::whereIn('account_id', $revenues)
            ->whereBetween('transaction_date', [$start, $end])
            ->where('is_paired', false)
            ->sum('debit');
            
        $expenses = Account::where('type', 'expense')->where('is_active', true)->pluck('id');
        $totalExpense = Journal::whereIn('account_id', $expenses)
            ->whereBetween('transaction_date', [$start, $end])
            ->where('is_paired', false)
            ->sum('debit') - Journal::whereIn('account_id', $expenses)
            ->whereBetween('transaction_date', [$start, $end])
            ->where('is_paired', false)
            ->sum('kredit');
            
        $netIncome = $totalRevenue - $totalExpense;
        $balance += $netIncome;
        echo "{$acc->code} - {$acc->name}: " . number_format($balance, 0, ',', '.') . " (termasuk Laba Bersih: " . number_format($netIncome, 0, ',', '.') . ")\n";
    } else {
        echo "{$acc->code} - {$acc->name}: " . number_format($balance, 0, ',', '.') . "\n";
    }
    $totalEkuitas += $balance;
}
echo "TOTAL EKUITAS: " . number_format($totalEkuitas, 0, ',', '.') . "\n\n";

echo "=== RINGKASAN ===\n";
echo "Total Aktiva: " . number_format($totalAktiva, 0, ',', '.') . "\n";
echo "Total Kewajiban: " . number_format($totalKewajiban, 0, ',', '.') . "\n";
echo "Total Ekuitas: " . number_format($totalEkuitas, 0, ',', '.') . "\n";
echo "Total Kewajiban + Ekuitas: " . number_format($totalKewajiban + $totalEkuitas, 0, ',', '.') . "\n\n";

if ($totalAktiva == ($totalKewajiban + $totalEkuitas)) {
    echo "✓ NERACA BALANCE!\n";
} else {
    $selisih = abs($totalAktiva - ($totalKewajiban + $totalEkuitas));
    echo "✗ TIDAK BALANCE! Selisih: " . number_format($selisih, 0, ',', '.') . "\n";
}
