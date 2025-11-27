<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Account;
use App\Models\Journal;

$start = '2025-01-01';
$end = '2025-11-27';

echo "=== CEK MODAL PEMILIK ===\n\n";

$modalPemilik = Account::where('name', 'like', '%modal%pemilik%')->first();

if ($modalPemilik) {
    echo "Account ID: {$modalPemilik->id}\n";
    echo "Account Code: {$modalPemilik->code}\n";
    echo "Account Name: {$modalPemilik->name}\n";
    echo "Account Type: {$modalPemilik->type}\n\n";
    
    $journals = Journal::where('account_id', $modalPemilik->id)
        ->whereBetween('transaction_date', [$start, $end])
        ->get();  // Ambil SEMUA jurnal (main + paired)
    
    echo "Jumlah jurnal: " . $journals->count() . "\n\n";
    
    $totalDebit = 0;
    $totalKredit = 0;
    
    foreach($journals as $j) {
        echo "Date: {$j->transaction_date} | Debit: " . number_format($j->debit, 0, ',', '.') . " | Kredit: " . number_format($j->kredit, 0, ',', '.') . " | Item: {$j->item}\n";
        $totalDebit += $j->debit;
        $totalKredit += $j->kredit;
    }
    
    echo "\nTotal Debit: " . number_format($totalDebit, 0, ',', '.') . "\n";
    echo "Total Kredit: " . number_format($totalKredit, 0, ',', '.') . "\n";
    
    // Modal Pemilik adalah equity, normal balance kredit
    // Jadi saldo = kredit - debit
    $saldo = $totalKredit - $totalDebit;
    echo "\nSaldo Modal Pemilik (Kredit - Debit): " . number_format($saldo, 0, ',', '.') . "\n";
} else {
    echo "Modal Pemilik account not found!\n";
}
