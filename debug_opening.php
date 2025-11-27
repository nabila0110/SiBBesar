<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Journal;

echo "=== CEK OPENING BALANCE JOURNALS ===\n\n";

$openingJournals = Journal::where('reference', 'OPENING-001')->get();

echo "Jumlah jurnal dengan reference OPENING-001: " . $openingJournals->count() . "\n\n";

foreach($openingJournals as $j) {
    echo "ID: {$j->id}\n";
    echo "Date: {$j->transaction_date}\n";
    echo "Account: {$j->account->name} (ID: {$j->account_id})\n";
    echo "Debit: " . number_format($j->debit, 0, ',', '.') . "\n";
    echo "Kredit: " . number_format($j->kredit, 0, ',', '.') . "\n";
    echo "is_paired: " . ($j->is_paired ? 'true' : 'false') . "\n";
    echo "paired_journal_id: " . ($j->paired_journal_id ?? 'null') . "\n";
    echo "---\n\n";
}

echo "\nTotal ALL journals: " . Journal::count() . "\n";
