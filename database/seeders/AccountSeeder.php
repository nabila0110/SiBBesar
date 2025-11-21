<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\AccountCategory;

class AccountSeeder extends Seeder
{
    public function run()
    {
        // Get category IDs
        $aktivaCategory = AccountCategory::where('code', '1')->first();
        $kewajibanCategory = AccountCategory::where('code', '2')->first();
        $ekuitasCategory = AccountCategory::where('code', '3')->first();
        $pendapatanCategory = AccountCategory::where('code', '4')->first();
        $bebanCategory = AccountCategory::where('code', '5')->first();

        $accounts = [
            // ASET (Category: 1)
            ['code' => '1100', 'name' => 'Kas', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_lancar', 'expense_type' => null, 'account_category_id' => $aktivaCategory->id],
            ['code' => '1200', 'name' => 'Bank', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_lancar', 'expense_type' => null, 'account_category_id' => $aktivaCategory->id],
            ['code' => '1300', 'name' => 'Piutang Usaha', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_lancar', 'expense_type' => null, 'account_category_id' => $aktivaCategory->id],
            ['code' => '2100', 'name' => 'Persediaan Barang', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_lancar', 'expense_type' => null, 'account_category_id' => $aktivaCategory->id],
            ['code' => '3100', 'name' => 'Peralatan', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_tetap', 'expense_type' => null, 'account_category_id' => $aktivaCategory->id],
            ['code' => '3200', 'name' => 'Kendaraan', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_tetap', 'expense_type' => null, 'account_category_id' => $aktivaCategory->id],
            ['code' => '3300', 'name' => 'Gedung', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_tetap', 'expense_type' => null, 'account_category_id' => $aktivaCategory->id],
            
            // LIABILITAS (Category: 2)
            ['code' => '1100', 'name' => 'Hutang Usaha', 'type' => 'liability', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'liabilitas_jangka_pendek', 'expense_type' => null, 'account_category_id' => $kewajibanCategory->id],
            ['code' => '1200', 'name' => 'Hutang Bank', 'type' => 'liability', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'liabilitas_jangka_pendek', 'expense_type' => null, 'account_category_id' => $kewajibanCategory->id],
            ['code' => '2100', 'name' => 'Hutang Jangka Panjang', 'type' => 'liability', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'liabilitas_jangka_panjang', 'expense_type' => null, 'account_category_id' => $kewajibanCategory->id],
            
            // EKUITAS (Category: 3)
            ['code' => '1100', 'name' => 'Modal', 'type' => 'equity', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'ekuitas', 'expense_type' => null, 'account_category_id' => $ekuitasCategory->id],
            ['code' => '1200', 'name' => 'Laba Ditahan', 'type' => 'equity', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'ekuitas', 'expense_type' => null, 'account_category_id' => $ekuitasCategory->id],
            
            // PENDAPATAN (Category: 4)
            ['code' => '1100', 'name' => 'Pendapatan Jasa', 'type' => 'revenue', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'pendapatan', 'expense_type' => null, 'account_category_id' => $pendapatanCategory->id],
            ['code' => '1200', 'name' => 'Pendapatan Lain-lain', 'type' => 'revenue', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'pendapatan', 'expense_type' => null, 'account_category_id' => $pendapatanCategory->id],
            
            // BEBAN (Category: 5)
            ['code' => '1100', 'name' => 'Beban Gaji/Upah', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional', 'account_category_id' => $bebanCategory->id],
            ['code' => '1200', 'name' => 'Beban Material', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional', 'account_category_id' => $bebanCategory->id],
            ['code' => '1300', 'name' => 'Beban Transport', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional', 'account_category_id' => $bebanCategory->id],
            ['code' => '1400', 'name' => 'Beban Administrasi', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional', 'account_category_id' => $bebanCategory->id],
            ['code' => '1500', 'name' => 'Beban ESM', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional', 'account_category_id' => $bebanCategory->id],
            ['code' => '1600', 'name' => 'Beban Listrik', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional', 'account_category_id' => $bebanCategory->id],
            ['code' => '1700', 'name' => 'Beban Telepon', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional', 'account_category_id' => $bebanCategory->id],
            ['code' => '1800', 'name' => 'Beban Sewa', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional', 'account_category_id' => $bebanCategory->id],
        ];

        foreach ($accounts as $account) {
            Account::create($account);
        }
    }
}
