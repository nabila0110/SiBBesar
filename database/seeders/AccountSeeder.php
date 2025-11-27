<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\AccountCategory;

class AccountSeeder extends Seeder
{
    public function run()
    {
        // Get category IDs dengan kode baru (1-1, 2-1, dst)
        $asetLancarCategory = AccountCategory::where('code', '1-1')->where('type', 'asset')->first();
        $kewajibanLancarCategory = AccountCategory::where('code', '2-1')->where('type', 'liability')->first();
        $modalCategory = AccountCategory::where('code', '3-1')->where('type', 'equity')->first();
        $pendapatanUsahaCategory = AccountCategory::where('code', '4-1')->where('type', 'revenue')->first();
        $bebanOperasionalCategory = AccountCategory::where('code', '5-1')->where('type', 'expense')->first();

        // Check if categories exist
        if (!$asetLancarCategory || !$kewajibanLancarCategory || !$modalCategory || !$pendapatanUsahaCategory || !$bebanOperasionalCategory) {
            $this->command->error('Categories not found! Please run AccountCategorySeeder first.');
            return;
        }

        $accounts = [
            // ASET LANCAR (1-1-xxxx)
            ['code' => '1-1-1100', 'name' => 'Kas', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'Assets', 'expense_type' => null, 'account_category_id' => $asetLancarCategory->id, 'description' => 'Uang tunai perusahaan'],
            ['code' => '1-1-1200', 'name' => 'Bank', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'Assets', 'expense_type' => null, 'account_category_id' => $asetLancarCategory->id, 'description' => 'Rekening bank perusahaan'],
            ['code' => '1-1-1300', 'name' => 'Piutang Usaha', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'Assets', 'expense_type' => null, 'account_category_id' => $asetLancarCategory->id, 'description' => 'Tagihan kepada pelanggan'],
            ['code' => '1-1-1400', 'name' => 'Persediaan Barang', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'Assets', 'expense_type' => null, 'account_category_id' => $asetLancarCategory->id, 'description' => 'Barang dagangan'],
            
            // KEWAJIBAN LANCAR (2-1-xxxx)
            ['code' => '2-1-1100', 'name' => 'Hutang Usaha', 'type' => 'liability', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'Liabilities', 'expense_type' => null, 'account_category_id' => $kewajibanLancarCategory->id, 'description' => 'Hutang kepada supplier'],
            ['code' => '2-1-1200', 'name' => 'Hutang Bank', 'type' => 'liability', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'Liabilities', 'expense_type' => null, 'account_category_id' => $kewajibanLancarCategory->id, 'description' => 'Pinjaman bank jangka pendek'],
            
            // MODAL (3-1-xxxx)
            ['code' => '3-1-1100', 'name' => 'Modal Pemilik', 'type' => 'equity', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'Equity', 'expense_type' => null, 'account_category_id' => $modalCategory->id, 'description' => 'Modal yang disetor pemilik'],
            ['code' => '3-1-1200', 'name' => 'Laba Ditahan', 'type' => 'equity', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'Equity', 'expense_type' => null, 'account_category_id' => $modalCategory->id, 'description' => 'Akumulasi laba yang tidak dibagi'],
            
            // PENDAPATAN (4-1-xxxx)
            ['code' => '4-1-1100', 'name' => 'Pendapatan Jasa', 'type' => 'revenue', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'Revenue', 'expense_type' => null, 'account_category_id' => $pendapatanUsahaCategory->id, 'description' => 'Pendapatan dari jasa'],
            ['code' => '4-1-1200', 'name' => 'Pendapatan Lain-lain', 'type' => 'revenue', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'Revenue', 'expense_type' => null, 'account_category_id' => $pendapatanUsahaCategory->id, 'description' => 'Pendapatan non-operasional'],
            
            // BEBAN (5-1-xxxx)
            ['code' => '5-1-1100', 'name' => 'Beban Gaji/Upah', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'Expenses', 'expense_type' => null, 'account_category_id' => $bebanOperasionalCategory->id, 'description' => 'Gaji karyawan dan upah pekerja'],
            ['code' => '5-1-1200', 'name' => 'Beban Material', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'Expenses', 'expense_type' => null, 'account_category_id' => $bebanOperasionalCategory->id, 'description' => 'Biaya bahan baku/material'],
            ['code' => '5-1-1300', 'name' => 'Beban Transport', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'Expenses', 'expense_type' => null, 'account_category_id' => $bebanOperasionalCategory->id, 'description' => 'Biaya transportasi'],
            ['code' => '5-1-1400', 'name' => 'Beban Administrasi', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'Expenses', 'expense_type' => null, 'account_category_id' => $bebanOperasionalCategory->id, 'description' => 'Biaya administrasi umum'],
            ['code' => '5-1-1500', 'name' => 'Beban Listrik & Air', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'Expenses', 'expense_type' => null, 'account_category_id' => $bebanOperasionalCategory->id, 'description' => 'Tagihan listrik dan air'],
            ['code' => '5-1-1600', 'name' => 'Beban Telepon & Internet', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'Expenses', 'expense_type' => null, 'account_category_id' => $bebanOperasionalCategory->id, 'description' => 'Biaya komunikasi'],
            ['code' => '5-1-1700', 'name' => 'Beban Sewa', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'Expenses', 'expense_type' => null, 'account_category_id' => $bebanOperasionalCategory->id, 'description' => 'Biaya sewa gedung/tempat'],
        ];

        foreach ($accounts as $accountData) {
            Account::updateOrCreate(
                [
                    'account_category_id' => $accountData['account_category_id'],
                    'code' => $accountData['code']
                ],
                $accountData
            );
        }

        $this->command->info('Created/Updated ' . count($accounts) . ' accounts.');
    }
}
