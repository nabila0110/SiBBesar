<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BukuBesarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete all existing data (respects foreign keys)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        JournalDetail::truncate();
        Journal::truncate();
        Account::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        echo "🗑  Data lama telah dihapus\n\n";

        // 1. Create Accounts (Chart of Accounts)
        $accounts = [
            // ASET
            ['code' => '1-1000', 'name' => 'Kas', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => 1],
            ['code' => '1-1100', 'name' => 'Bank BCA', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => 1],
            ['code' => '1-1200', 'name' => 'Piutang Usaha', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => 1],
            ['code' => '1-1300', 'name' => 'Persediaan Barang', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => 1],
            ['code' => '1-2000', 'name' => 'Peralatan Kantor', 'type' => 'asset', 'normal_balance' => 'debit', 'is_active' => 1],
            
            // KEWAJIBAN
            ['code' => '2-1000', 'name' => 'Hutang Usaha', 'type' => 'liability', 'normal_balance' => 'credit', 'is_active' => 1],
            ['code' => '2-1100', 'name' => 'Hutang Bank', 'type' => 'liability', 'normal_balance' => 'credit', 'is_active' => 1],
            
            // MODAL
            ['code' => '3-1000', 'name' => 'Modal Pemilik', 'type' => 'equity', 'normal_balance' => 'credit', 'is_active' => 1],
            ['code' => '3-2000', 'name' => 'Laba Ditahan', 'type' => 'equity', 'normal_balance' => 'credit', 'is_active' => 1],
            
            // PENDAPATAN
            ['code' => '4-1000', 'name' => 'Pendapatan Jasa', 'type' => 'revenue', 'normal_balance' => 'credit', 'is_active' => 1],
            ['code' => '4-2000', 'name' => 'Pendapatan Lain-lain', 'type' => 'revenue', 'normal_balance' => 'credit', 'is_active' => 1],
            
            // BEBAN
            ['code' => '5-1000', 'name' => 'Beban Gaji', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => 1],
            ['code' => '5-1100', 'name' => 'Beban Listrik', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => 1],
            ['code' => '5-1200', 'name' => 'Beban Sewa', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => 1],
            ['code' => '5-1300', 'name' => 'Beban Perlengkapan', 'type' => 'expense', 'normal_balance' => 'debit', 'is_active' => 1],
        ];

        $accountModels = [];
        foreach ($accounts as $account) {
            $accountModels[$account['code']] = Account::create($account);
        }

        // Default user ID for created_by
        $defaultUserId = 1;

        // 2. Create Journals with Details (Transactions)
        
        // Transaksi 1: Setoran Modal Awal (1 bulan lalu)
        $journal1 = Journal::create([
            'journal_no' => 'JNL-001',
            'transaction_date' => Carbon::now()->subMonth()->startOfMonth()->toDateString(),
            'description' => 'Setoran Modal Awal',
            'reference' => 'JV-001',
            'status' => 'posted',
            'created_by' => $defaultUserId,
        ]);
        JournalDetail::create([
            'journal_id' => $journal1->id,
            'account_id' => $accountModels['1-1000']->id,
            'description' => 'Penerimaan modal tunai',
            'debit' => 50000000,
            'credit' => 0,
            'line_number' => 1,
        ]);
        JournalDetail::create([
            'journal_id' => $journal1->id,
            'account_id' => $accountModels['3-1000']->id,
            'description' => 'Modal pemilik',
            'debit' => 0,
            'credit' => 50000000,
            'line_number' => 2,
        ]);

        // Transaksi 2: Pembelian Peralatan (1 bulan lalu)
        $journal2 = Journal::create([
            'journal_no' => 'JNL-002',
            'transaction_date' => Carbon::now()->subMonth()->addDays(2)->toDateString(),
            'description' => 'Pembelian Peralatan Kantor',
            'reference' => 'JV-002',
            'status' => 'posted',
            'created_by' => $defaultUserId,
        ]);
        JournalDetail::create([
            'journal_id' => $journal2->id,
            'account_id' => $accountModels['1-2000']->id,
            'description' => 'Pembelian komputer dan meja',
            'debit' => 15000000,
            'credit' => 0,
            'line_number' => 1,
        ]);
        JournalDetail::create([
            'journal_id' => $journal2->id,
            'account_id' => $accountModels['1-1000']->id,
            'description' => 'Pembayaran tunai',
            'debit' => 0,
            'credit' => 15000000,
            'line_number' => 2,
        ]);

        // Transaksi 3: Transfer ke Bank (bulan ini - awal)
        $journal3 = Journal::create([
            'journal_no' => 'JNL-003',
            'transaction_date' => Carbon::now()->startOfMonth()->addDays(1)->toDateString(),
            'description' => 'Transfer Dana ke Bank BCA',
            'reference' => 'JV-003',
            'status' => 'posted',
            'created_by' => $defaultUserId,
        ]);
        JournalDetail::create([
            'journal_id' => $journal3->id,
            'account_id' => $accountModels['1-1100']->id,
            'description' => 'Transfer ke rekening BCA',
            'debit' => 20000000,
            'credit' => 0,
            'line_number' => 1,
        ]);
        JournalDetail::create([
            'journal_id' => $journal3->id,
            'account_id' => $accountModels['1-1000']->id,
            'description' => 'Pengambilan dari kas',
            'debit' => 0,
            'credit' => 20000000,
            'line_number' => 2,
        ]);

        // Transaksi 4: Pendapatan Jasa (bulan ini)
        $journal4 = Journal::create([
            'journal_no' => 'JNL-004',
            'transaction_date' => Carbon::now()->startOfMonth()->addDays(5)->toDateString(),
            'description' => 'Pendapatan Jasa Konsultasi',
            'reference' => 'JV-004',
            'status' => 'posted',
            'created_by' => $defaultUserId,
        ]);
        JournalDetail::create([
            'journal_id' => $journal4->id,
            'account_id' => $accountModels['1-1100']->id,
            'description' => 'Penerimaan pembayaran via bank',
            'debit' => 8500000,
            'credit' => 0,
            'line_number' => 1,
        ]);
        JournalDetail::create([
            'journal_id' => $journal4->id,
            'account_id' => $accountModels['4-1000']->id,
            'description' => 'Pendapatan jasa konsultasi',
            'debit' => 0,
            'credit' => 8500000,
            'line_number' => 2,
        ]);

        // Transaksi 5: Pembelian Persediaan (bulan ini)
        $journal5 = Journal::create([
            'journal_no' => 'JNL-005',
            'transaction_date' => Carbon::now()->startOfMonth()->addDays(7)->toDateString(),
            'description' => 'Pembelian Persediaan Barang',
            'reference' => 'JV-005',
            'status' => 'posted',
            'created_by' => $defaultUserId,
        ]);
        JournalDetail::create([
            'journal_id' => $journal5->id,
            'account_id' => $accountModels['1-1300']->id,
            'description' => 'Pembelian barang dagangan',
            'debit' => 5000000,
            'credit' => 0,
            'line_number' => 1,
        ]);
        JournalDetail::create([
            'journal_id' => $journal5->id,
            'account_id' => $accountModels['2-1000']->id,
            'description' => 'Hutang kepada supplier',
            'debit' => 0,
            'credit' => 5000000,
            'line_number' => 2,
        ]);

        // Transaksi 6: Pembayaran Gaji (bulan ini)
        $journal6 = Journal::create([
            'journal_no' => 'JNL-006',
            'transaction_date' => Carbon::now()->startOfMonth()->addDays(10)->toDateString(),
            'description' => 'Pembayaran Gaji Karyawan',
            'reference' => 'JV-006',
            'status' => 'posted',
            'created_by' => $defaultUserId,
        ]);
        JournalDetail::create([
            'journal_id' => $journal6->id,
            'account_id' => $accountModels['5-1000']->id,
            'description' => 'Beban gaji bulan ini',
            'debit' => 6000000,
            'credit' => 0,
            'line_number' => 1,
        ]);
        JournalDetail::create([
            'journal_id' => $journal6->id,
            'account_id' => $accountModels['1-1100']->id,
            'description' => 'Transfer gaji via bank',
            'debit' => 0,
            'credit' => 6000000,
            'line_number' => 2,
        ]);

        // Transaksi 7: Pembayaran Sewa (bulan ini)
        $journal7 = Journal::create([
            'journal_no' => 'JNL-007',
            'transaction_date' => Carbon::now()->startOfMonth()->addDays(12)->toDateString(),
            'description' => 'Pembayaran Sewa Kantor',
            'reference' => 'JV-007',
            'status' => 'posted',
            'created_by' => $defaultUserId,
        ]);
        JournalDetail::create([
            'journal_id' => $journal7->id,
            'account_id' => $accountModels['5-1200']->id,
            'description' => 'Beban sewa kantor',
            'debit' => 3000000,
            'credit' => 0,
            'line_number' => 1,
        ]);
        JournalDetail::create([
            'journal_id' => $journal7->id,
            'account_id' => $accountModels['1-1000']->id,
            'description' => 'Pembayaran tunai',
            'debit' => 0,
            'credit' => 3000000,
            'line_number' => 2,
        ]);

        // Transaksi 8: Pembayaran Listrik (bulan ini)
        $journal8 = Journal::create([
            'journal_no' => 'JNL-008',
            'transaction_date' => Carbon::now()->startOfMonth()->addDays(15)->toDateString(),
            'description' => 'Pembayaran Listrik',
            'reference' => 'JV-008',
            'status' => 'posted',
            'created_by' => $defaultUserId,
        ]);
        JournalDetail::create([
            'journal_id' => $journal8->id,
            'account_id' => $accountModels['5-1100']->id,
            'description' => 'Beban listrik',
            'debit' => 750000,
            'credit' => 0,
            'line_number' => 1,
        ]);
        JournalDetail::create([
            'journal_id' => $journal8->id,
            'account_id' => $accountModels['1-1000']->id,
            'description' => 'Pembayaran tunai',
            'debit' => 0,
            'credit' => 750000,
            'line_number' => 2,
        ]);

        // Transaksi 9: Penjualan Kredit (bulan ini)
        $journal9 = Journal::create([
            'journal_no' => 'JNL-009',
            'transaction_date' => Carbon::now()->startOfMonth()->addDays(18)->toDateString(),
            'description' => 'Penjualan Kredit',
            'reference' => 'JV-009',
            'status' => 'posted',
            'created_by' => $defaultUserId,
        ]);
        JournalDetail::create([
            'journal_id' => $journal9->id,
            'account_id' => $accountModels['1-1200']->id,
            'description' => 'Piutang dari penjualan',
            'debit' => 4500000,
            'credit' => 0,
            'line_number' => 1,
        ]);
        JournalDetail::create([
            'journal_id' => $journal9->id,
            'account_id' => $accountModels['4-1000']->id,
            'description' => 'Pendapatan jasa',
            'debit' => 0,
            'credit' => 4500000,
            'line_number' => 2,
        ]);

        // Transaksi 10: Pelunasan Sebagian Hutang (bulan ini)
        $journal10 = Journal::create([
            'journal_no' => 'JNL-010',
            'transaction_date' => Carbon::now()->startOfMonth()->addDays(20)->toDateString(),
            'description' => 'Pelunasan Sebagian Hutang Usaha',
            'reference' => 'JV-010',
            'status' => 'posted',
            'created_by' => $defaultUserId,
        ]);
        JournalDetail::create([
            'journal_id' => $journal10->id,
            'account_id' => $accountModels['2-1000']->id,
            'description' => 'Pembayaran hutang supplier',
            'debit' => 2000000,
            'credit' => 0,
            'line_number' => 1,
        ]);
        JournalDetail::create([
            'journal_id' => $journal10->id,
            'account_id' => $accountModels['1-1100']->id,
            'description' => 'Transfer via bank',
            'debit' => 0,
            'credit' => 2000000,
            'line_number' => 2,
        ]);

        echo "✅ Seeder BukuBesar berhasil dijalankan!\n";
        echo "📊 Dibuat: 15 Akun, 10 Jurnal dengan 20 Detail Transaksi\n";
        echo "📅 Periode: " . Carbon::now()->subMonth()->format('M Y') . " - " . Carbon::now()->format('M Y') . "\n";
        echo "ℹ  Semua jurnal berstatus 'posted' dan siap ditampilkan di Buku Besar\n";
    }
}