<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Journal;

$start = '2025-01-01';
$end = '2025-11-27';

echo "=== CEK DEBIT/KREDIT BALANCE ===\n\n";

// Total all journals
$allDebit = Journal::sum('debit');
$allKredit = Journal::sum('kredit');
echo "Total ALL Journals:\n";
echo "Debit: " . number_format($allDebit, 0, ',', '.') . "\n";
echo "Kredit: " . number_format($allKredit, 0, ',', '.') . "\n";
echo ($allDebit == $allKredit ? "✓ BALANCE\n" : "✗ TIDAK BALANCE! Selisih: " . number_format(abs($allDebit - $allKredit), 0, ',', '.') . "\n");

echo "\n";

// Total main journals only
$mainDebit = Journal::where('is_paired', false)->sum('debit');
$mainKredit = Journal::where('is_paired', false)->sum('kredit');
echo "Total MAIN Journals (is_paired = false):\n";
echo "Debit: " . number_format($mainDebit, 0, ',', '.') . "\n";
echo "Kredit: " . number_format($mainKredit, 0, ',', '.') . "\n";
echo ($mainDebit == $mainKredit ? "✓ BALANCE\n" : "✗ TIDAK BALANCE! Selisih: " . number_format(abs($mainDebit - $mainKredit), 0, ',', '.') . "\n");

echo "\n";

// Count journals
$totalJournals = Journal::count();
$mainJournals = Journal::where('is_paired', false)->count();
$pairedJournals = Journal::where('is_paired', true)->count();

echo "Total Journals: $totalJournals\n";
echo "Main Journals: $mainJournals\n";
echo "Paired Journals: $pairedJournals\n";
