<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\AccountCategory;
use App\Models\Account;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed users only if none exist
        if (User::count() === 0) {
            $this->call(UserSeeder::class);
        }

        // Create company only if none exists
        if (Company::count() === 0) {
            Company::create([
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'name' => 'PT. Example Company',
                'address_line1' => 'Jakarta, Indonesia',
                'phone' => '021-1234567',
                'email' => 'info@example.com',
            ]);
        }

        // Create account categories and accounts
        $this->createAccountStructure();
        
        // Seed assets and journals with test data
        $this->call([
            AssetSeeder::class,
            JournalSeeder::class,
        ]);
    }

    private function createAccountStructure()
    {
        // Check if account structure already exists
        if (AccountCategory::count() > 0) {
            return;
        }
        
        // 1. ASSETS
        $assetCategory = AccountCategory::create([
            'code' => '1',
            'name' => 'AKTIVA',
            'type' => 'asset',
            'normal_balance' => 'debit',
        ]);

        $accounts = [
            // Current Assets
            ['1-1000', 'Kas', 'asset', 'debit'],
            ['1-1100', 'Bank', 'asset', 'debit'],
            ['1-1200', 'Piutang Usaha', 'asset', 'debit'],
            ['1-1300', 'Piutang Lain-lain', 'asset', 'debit'],
            ['1-1400', 'Persediaan Barang', 'asset', 'debit'],
            ['1-1500', 'Uang Muka', 'asset', 'debit'],
            
            // Fixed Assets
            ['1-2000', 'Tanah', 'asset', 'debit'],
            ['1-2100', 'Bangunan', 'asset', 'debit'],
            ['1-2200', 'Kendaraan', 'asset', 'debit'],
            ['1-2300', 'Peralatan', 'asset', 'debit'],
            ['1-2400', 'Akum. Penyusutan Bangunan', 'asset', 'credit'],
            ['1-2500', 'Akum. Penyusutan Kendaraan', 'asset', 'credit'],
        ];

        foreach ($accounts as $acc) {
            Account::create([
                'account_category_id' => $assetCategory->id,
                'code' => $acc[0],
                'name' => $acc[1],
                'type' => $acc[2],
                'normal_balance' => $acc[3],
                'is_active' => true,
            ]);
        }

        // 2. LIABILITIES
        $liabilityCategory = AccountCategory::create([
            'code' => '2',
            'name' => 'KEWAJIBAN',
            'type' => 'liability',
            'normal_balance' => 'credit',
        ]);

        $liabilities = [
            ['2-1000', 'Hutang Usaha', 'liability', 'credit'],
            ['2-1100', 'Hutang Lain-lain', 'liability', 'credit'],
            ['2-1200', 'Hutang Bank', 'liability', 'credit'],
            ['2-1300', 'Hutang Pajak', 'liability', 'credit'],
        ];

        foreach ($liabilities as $acc) {
            Account::create([
                'account_category_id' => $liabilityCategory->id,
                'code' => $acc[0],
                'name' => $acc[1],
                'type' => $acc[2],
                'normal_balance' => $acc[3],
                'is_active' => true,
            ]);
        }

        // 3. EQUITY
        $equityCategory = AccountCategory::create([
            'code' => '3',
            'name' => 'EKUITAS',
            'type' => 'equity',
            'normal_balance' => 'credit',
        ]);

        $equities = [
            ['3-1000', 'Modal', 'equity', 'credit'],
            ['3-2000', 'Laba Ditahan', 'equity', 'credit'],
            ['3-3000', 'Laba (Rugi) Bersih', 'equity', 'credit'],
        ];

        foreach ($equities as $acc) {
            Account::create([
                'account_category_id' => $equityCategory->id,
                'code' => $acc[0],
                'name' => $acc[1],
                'type' => $acc[2],
                'normal_balance' => $acc[3],
                'is_active' => true,
            ]);
        }

        // 4. REVENUE
        $revenueCategory = AccountCategory::create([
            'code' => '4',
            'name' => 'PENDAPATAN',
            'type' => 'revenue',
            'normal_balance' => 'credit',
        ]);

        $revenues = [
            ['4-1000', 'Pendapatan Jasa', 'revenue', 'credit'],
            ['4-2000', 'Pendapatan Lain-lain', 'revenue', 'credit'],
        ];

        foreach ($revenues as $acc) {
            Account::create([
                'account_category_id' => $revenueCategory->id,
                'code' => $acc[0],
                'name' => $acc[1],
                'type' => $acc[2],
                'normal_balance' => $acc[3],
                'is_active' => true,
            ]);
        }

        // 5. EXPENSES
        $expenseCategory = AccountCategory::create([
            'code' => '5',
            'name' => 'BIAYA',
            'type' => 'expense',
            'normal_balance' => 'debit',
        ]);

        $expenses = [
            ['5-1000', 'Biaya Gaji', 'expense', 'debit'],
            ['5-1100', 'Biaya Listrik', 'expense', 'debit'],
            ['5-1200', 'Biaya Telepon', 'expense', 'debit'],
            ['5-1300', 'Biaya Sewa', 'expense', 'debit'],
            ['5-1400', 'Biaya Penyusutan', 'expense', 'debit'],
            ['5-2000', 'Biaya Lain-lain', 'expense', 'debit'],
        ];

        foreach ($expenses as $acc) {
            Account::create([
                'account_category_id' => $expenseCategory->id,
                'code' => $acc[0],
                'name' => $acc[1],
                'type' => $acc[2],
                'normal_balance' => $acc[3],
                'is_active' => true,
            ]);
        }
    }
}
