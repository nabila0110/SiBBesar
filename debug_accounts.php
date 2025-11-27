<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Account;

echo "=== DAFTAR AKUN ===\n\n";

$accounts = Account::orderBy('code')->get();
foreach($accounts as $acc) {
    echo "{$acc->code} - {$acc->name} [{$acc->type}] " . ($acc->is_active ? '✓' : '✗') . "\n";
}
