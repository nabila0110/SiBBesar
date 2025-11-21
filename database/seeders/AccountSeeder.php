<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            // ASET
            ['code' => '1-1100', 'name' => 'Kas', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_lancar', 'expense_type' => null],
            ['code' => '1-1200', 'name' => 'Bank', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_lancar', 'expense_type' => null],
            ['code' => '1-1300', 'name' => 'Piutang Usaha', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_lancar', 'expense_type' => null],
            ['code' => '1-2100', 'name' => 'Persediaan Barang', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_lancar', 'expense_type' => null],
            ['code' => '1-3100', 'name' => 'Peralatan', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_tetap', 'expense_type' => null],
            ['code' => '1-3200', 'name' => 'Kendaraan', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_tetap', 'expense_type' => null],
            ['code' => '1-3300', 'name' => 'Gedung', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'aset_tetap', 'expense_type' => null],
            
            // LIABILITAS
            ['code' => '2-1100', 'name' => 'Hutang Usaha', 'type' => 'liability', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'liabilitas_jangka_pendek', 'expense_type' => null],
            ['code' => '2-1200', 'name' => 'Hutang Bank', 'type' => 'liability', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'liabilitas_jangka_pendek', 'expense_type' => null],
            ['code' => '2-2100', 'name' => 'Hutang Jangka Panjang', 'type' => 'liability', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'liabilitas_jangka_panjang', 'expense_type' => null],
            
            // EKUITAS
            ['code' => '3-1100', 'name' => 'Modal', 'type' => 'equity', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'ekuitas', 'expense_type' => null],
            ['code' => '3-1200', 'name' => 'Laba Ditahan', 'type' => 'equity', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'ekuitas', 'expense_type' => null],
            
            // PENDAPATAN
            ['code' => '4-1100', 'name' => 'Pendapatan Jasa', 'type' => 'revenue', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'pendapatan', 'expense_type' => null],
            ['code' => '4-1200', 'name' => 'Pendapatan Lain-lain', 'type' => 'revenue', 'normal_balance' => 'credit', 'is_active' => true, 'group' => 'pendapatan', 'expense_type' => null],
            
            // BEBAN
            ['code' => '5-1100', 'name' => 'Beban Gaji/Upah', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional'],
            ['code' => '5-1200', 'name' => 'Beban Material', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional'],
            ['code' => '5-1300', 'name' => 'Beban Transport', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional'],
            ['code' => '5-1400', 'name' => 'Beban Administrasi', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional'],
            ['code' => '5-1500', 'name' => 'Beban ESM', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional'],
            ['code' => '5-1600', 'name' => 'Beban Listrik', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional'],
            ['code' => '5-1700', 'name' => 'Beban Telepon', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional'],
            ['code' => '5-1800', 'name' => 'Beban Sewa', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => true, 'group' => 'beban_operasional', 'expense_type' => 'operasional'],
        ];

        foreach ($accounts as $account) {
            Account::create($account);
        }
    }
}
