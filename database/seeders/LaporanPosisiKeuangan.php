<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountCategory;
use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalDetail;
use Illuminate\Support\Facades\DB;

class LaporanPosisiKeuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('accounts')->delete();
        DB::table('account_categories')->delete();

        // ========== ASSET CATEGORIES ==========
        $kasBank = AccountCategory::create([
            'code' => '1100',
            'name' => 'Kas & Bank',
            'type' => 'asset',
            'description' => 'Kas dan rekening bank',
        ]);

        $piutang = AccountCategory::create([
            'code' => '1200',
            'name' => 'Piutang',
            'type' => 'asset',
            'description' => 'Piutang usaha dan lainnya',
        ]);

        $persediaan = AccountCategory::create([
            'code' => '1400',
            'name' => 'Persediaan',
            'type' => 'asset',
            'description' => 'Persediaan barang',
        ]);

        $asetTetap = AccountCategory::create([
            'code' => '1500',
            'name' => 'Aset Tetap',
            'type' => 'asset',
            'description' => 'Aset tetap dan penyusutan',
        ]);

        // ========== LIABILITY CATEGORIES ==========
        $hutangJangkaPendek = AccountCategory::create([
            'code' => '2100',
            'name' => 'Hutang Jangka Pendek',
            'type' => 'liability',
            'description' => 'Hutang usaha dan jangka pendek',
        ]);

        $hutangJangkaPanjang = AccountCategory::create([
            'code' => '2200',
            'name' => 'Hutang Jangka Panjang',
            'type' => 'liability',
            'description' => 'Hutang jangka panjang',
        ]);

        // ========== EQUITY CATEGORIES ==========
        $modal = AccountCategory::create([
            'code' => '3100',
            'name' => 'Modal',
            'type' => 'equity',
            'description' => 'Modal pemilik',
        ]);

        $labaRugi = AccountCategory::create([
            'code' => '3200',
            'name' => 'Laba Ditahan',
            'type' => 'equity',
            'description' => 'Laba rugi tahun berjalan',
        ]);

        // ========== REVENUE CATEGORIES ==========
        $pendapatan = AccountCategory::create([
            'code' => '4100',
            'name' => 'Pendapatan Usaha',
            'type' => 'revenue',
            'description' => 'Pendapatan dari operasional',
        ]);

        $pendapatanLain = AccountCategory::create([
            'code' => '4200',
            'name' => 'Pendapatan Lain-lain',
            'type' => 'revenue',
            'description' => 'Pendapatan di luar usaha',
        ]);

        // ========== EXPENSE CATEGORIES ==========
        $bebanOperasional = AccountCategory::create([
            'code' => '5100',
            'name' => 'Beban Operasional',
            'type' => 'expense',
            'description' => 'Beban operasional perusahaan',
        ]);

        $bebanLain = AccountCategory::create([
            'code' => '5200',
            'name' => 'Beban Lain-lain',
            'type' => 'expense',
            'description' => 'Beban di luar operasional',
        ]);

        // ========== ACCOUNTS - ASSETS ==========
        Account::create([
            'code' => '1101',
            'name' => 'Kas Ditangan',
            'account_category_id' => $kasBank->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '1102',
            'name' => 'Kas di Bank BCA',
            'account_category_id' => $kasBank->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '1103',
            'name' => 'Kas di Bank Mandiri',
            'account_category_id' => $kasBank->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '1201',
            'name' => 'Piutang Usaha',
            'account_category_id' => $piutang->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '1202',
            'name' => 'Piutang Karyawan',
            'account_category_id' => $piutang->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '1401',
            'name' => 'Persediaan Barang Dagang',
            'account_category_id' => $persediaan->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '1501',
            'name' => 'Tanah',
            'account_category_id' => $asetTetap->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '1502',
            'name' => 'Bangunan',
            'account_category_id' => $asetTetap->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '1503',
            'name' => 'Kendaraan',
            'account_category_id' => $asetTetap->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '1504',
            'name' => 'Peralatan Kantor',
            'account_category_id' => $asetTetap->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '1505',
            'name' => 'Akumulasi Penyusutan',
            'account_category_id' => $asetTetap->id,
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        // ========== ACCOUNTS - LIABILITIES ==========
        Account::create([
            'code' => '2101',
            'name' => 'Hutang Usaha',
            'account_category_id' => $hutangJangkaPendek->id,
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '2102',
            'name' => 'Hutang Gaji',
            'account_category_id' => $hutangJangkaPendek->id,
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '2103',
            'name' => 'Hutang Pajak',
            'account_category_id' => $hutangJangkaPendek->id,
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '2201',
            'name' => 'Hutang Bank Jangka Panjang',
            'account_category_id' => $hutangJangkaPanjang->id,
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        // ========== ACCOUNTS - EQUITY ==========
        Account::create([
            'code' => '3101',
            'name' => 'Modal Pemilik',
            'account_category_id' => $modal->id,
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '3102',
            'name' => 'Prive',
            'account_category_id' => $modal->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '3201',
            'name' => 'Laba Ditahan',
            'account_category_id' => $labaRugi->id,
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '3202',
            'name' => 'Laba Rugi Tahun Berjalan',
            'account_category_id' => $labaRugi->id,
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        // ========== ACCOUNTS - REVENUE ==========
        Account::create([
            'code' => '4101',
            'name' => 'Pendapatan Penjualan',
            'account_category_id' => $pendapatan->id,
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '4102',
            'name' => 'Pendapatan Jasa',
            'account_category_id' => $pendapatan->id,
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '4201',
            'name' => 'Pendapatan Bunga',
            'account_category_id' => $pendapatanLain->id,
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '4202',
            'name' => 'Pendapatan Sewa',
            'account_category_id' => $pendapatanLain->id,
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        // ========== ACCOUNTS - EXPENSES ==========
        Account::create([
            'code' => '5101',
            'name' => 'Beban Gaji',
            'account_category_id' => $bebanOperasional->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '5102',
            'name' => 'Beban Listrik',
            'account_category_id' => $bebanOperasional->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '5103',
            'name' => 'Beban Air',
            'account_category_id' => $bebanOperasional->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '5104',
            'name' => 'Beban Sewa Gedung',
            'account_category_id' => $bebanOperasional->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '5105',
            'name' => 'Beban Perlengkapan Kantor',
            'account_category_id' => $bebanOperasional->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '5106',
            'name' => 'Beban Transportasi',
            'account_category_id' => $bebanOperasional->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '5107',
            'name' => 'Beban Penyusutan',
            'account_category_id' => $bebanOperasional->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '5201',
            'name' => 'Beban Bunga',
            'account_category_id' => $bebanLain->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '5202',
            'name' => 'Beban Administrasi Bank',
            'account_category_id' => $bebanLain->id,
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        $this->command->info('✅ Account Categories and Accounts seeded successfully!');
    }
}