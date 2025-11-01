<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalDetail;
use Carbon\Carbon;

class LaporanPosisiKeuanganDataSeeder extends Seeder
{
    /**
     * Run the database seeds - adds sample journal transactions to populate Laporan Posisi Keuangan
     */
    public function run(): void
    {
        // Get accounts - these should already exist from previous seeders
        $accounts = Account::all()->keyBy('code');

        if ($accounts->isEmpty()) {
            $this->command->warn('⚠️ No accounts found! Please run account seeder first.');
            return;
        }

        // Delete existing journals to start fresh
        JournalDetail::truncate();
        Journal::truncate();

        $journalDate = Carbon::now()->startOfMonth();

        // ========== Transaction 1: Initial Capital (Modal) ==========
        $journal1 = Journal::create([
            'transaction_date' => $journalDate->copy()->addDays(1),
            'description' => 'Setoran Modal Awal',
            'reference_no' => 'JU-001',
            'notes' => 'Investasi awal dari pemilik',
        ]);

        // Kas in / Modal out
        if ($accounts->has('1101')) {
            JournalDetail::create([
                'journal_id' => $journal1->id,
                'account_id' => $accounts['1101']->id,
                'debit' => 500000000,
                'credit' => 0,
            ]);
        }
        if ($accounts->has('3101')) {
            JournalDetail::create([
                'journal_id' => $journal1->id,
                'account_id' => $accounts['3101']->id,
                'debit' => 0,
                'credit' => 500000000,
            ]);
        }

        // ========== Transaction 2: Purchase inventory ==========
        $journal2 = Journal::create([
            'transaction_date' => $journalDate->copy()->addDays(2),
            'description' => 'Pembelian Persediaan Barang',
            'reference_no' => 'JU-002',
            'notes' => 'Pembelian barang dagang',
        ]);

        // Persediaan in / Kas out
        if ($accounts->has('1401') && $accounts->has('1101')) {
            JournalDetail::create([
                'journal_id' => $journal2->id,
                'account_id' => $accounts['1401']->id,
                'debit' => 150000000,
                'credit' => 0,
            ]);
            JournalDetail::create([
                'journal_id' => $journal2->id,
                'account_id' => $accounts['1101']->id,
                'debit' => 0,
                'credit' => 150000000,
            ]);
        }

        // ========== Transaction 3: Sales Revenue ==========
        $journal3 = Journal::create([
            'transaction_date' => $journalDate->copy()->addDays(3),
            'description' => 'Penjualan Barang Dagang',
            'reference_no' => 'JU-003',
            'notes' => 'Penjualan tunai',
        ]);

        // Kas in / Revenue out
        if ($accounts->has('1101') && $accounts->has('4101')) {
            JournalDetail::create([
                'journal_id' => $journal3->id,
                'account_id' => $accounts['1101']->id,
                'debit' => 200000000,
                'credit' => 0,
            ]);
            JournalDetail::create([
                'journal_id' => $journal3->id,
                'account_id' => $accounts['4101']->id,
                'debit' => 0,
                'credit' => 200000000,
            ]);
        }

        // ========== Transaction 4: Purchase Fixed Assets ==========
        $journal4 = Journal::create([
            'transaction_date' => $journalDate->copy()->addDays(4),
            'description' => 'Pembelian Kendaraan',
            'reference_no' => 'JU-004',
            'notes' => 'Pembelian kendaraan operasional',
        ]);

        // Kendaraan in / Kas out
        if ($accounts->has('1503') && $accounts->has('1101')) {
            JournalDetail::create([
                'journal_id' => $journal4->id,
                'account_id' => $accounts['1503']->id,
                'debit' => 250000000,
                'credit' => 0,
            ]);
            JournalDetail::create([
                'journal_id' => $journal4->id,
                'account_id' => $accounts['1101']->id,
                'debit' => 0,
                'credit' => 250000000,
            ]);
        }

        // ========== Transaction 5: Operating Expenses ==========
        $journal5 = Journal::create([
            'transaction_date' => $journalDate->copy()->addDays(5),
            'description' => 'Pembayaran Gaji Karyawan',
            'reference_no' => 'JU-005',
            'notes' => 'Gaji bulan ini',
        ]);

        // Expense in / Kas out
        if ($accounts->has('5101') && $accounts->has('1101')) {
            JournalDetail::create([
                'journal_id' => $journal5->id,
                'account_id' => $accounts['5101']->id,
                'debit' => 50000000,
                'credit' => 0,
            ]);
            JournalDetail::create([
                'journal_id' => $journal5->id,
                'account_id' => $accounts['1101']->id,
                'debit' => 0,
                'credit' => 50000000,
            ]);
        }

        // ========== Transaction 6: Accounts Payable ==========
        $journal6 = Journal::create([
            'transaction_date' => $journalDate->copy()->addDays(6),
            'description' => 'Pembelian Barang Secara Kredit',
            'reference_no' => 'JU-006',
            'notes' => 'Hutang ke supplier',
        ]);

        // Persediaan in / Hutang out
        if ($accounts->has('1401') && $accounts->has('2101')) {
            JournalDetail::create([
                'journal_id' => $journal6->id,
                'account_id' => $accounts['1401']->id,
                'debit' => 100000000,
                'credit' => 0,
            ]);
            JournalDetail::create([
                'journal_id' => $journal6->id,
                'account_id' => $accounts['2101']->id,
                'debit' => 0,
                'credit' => 100000000,
            ]);
        }

        // ========== Transaction 7: Accounts Receivable ==========
        $journal7 = Journal::create([
            'transaction_date' => $journalDate->copy()->addDays(7),
            'description' => 'Penjualan Barang Secara Kredit',
            'reference_no' => 'JU-007',
            'notes' => 'Piutang dari customer',
        ]);

        // Piutang in / Revenue out
        if ($accounts->has('1201') && $accounts->has('4101')) {
            JournalDetail::create([
                'journal_id' => $journal7->id,
                'account_id' => $accounts['1201']->id,
                'debit' => 75000000,
                'credit' => 0,
            ]);
            JournalDetail::create([
                'journal_id' => $journal7->id,
                'account_id' => $accounts['4101']->id,
                'debit' => 0,
                'credit' => 75000000,
            ]);
        }

        // ========== Transaction 8: Utility Expenses ==========
        $journal8 = Journal::create([
            'transaction_date' => $journalDate->copy()->addDays(8),
            'description' => 'Pembayaran Listrik dan Air',
            'reference_no' => 'JU-008',
            'notes' => 'Tagihan utilitas bulanan',
        ]);

        // Expenses in / Kas out
        if ($accounts->has('5102') && $accounts->has('5103') && $accounts->has('1101')) {
            JournalDetail::create([
                'journal_id' => $journal8->id,
                'account_id' => $accounts['5102']->id,
                'debit' => 10000000,
                'credit' => 0,
            ]);
            JournalDetail::create([
                'journal_id' => $journal8->id,
                'account_id' => $accounts['5103']->id,
                'debit' => 5000000,
                'credit' => 0,
            ]);
            JournalDetail::create([
                'journal_id' => $journal8->id,
                'account_id' => $accounts['1101']->id,
                'debit' => 0,
                'credit' => 15000000,
            ]);
        }

        // ========== Transaction 9: Depreciation ==========
        $journal9 = Journal::create([
            'transaction_date' => $journalDate->copy()->endOfMonth(),
            'description' => 'Pencatatan Penyusutan Aset',
            'reference_no' => 'JU-009',
            'notes' => 'Penyusutan bulan ini',
        ]);

        // Depreciation expense in / Accumulated depreciation out
        if ($accounts->has('5107') && $accounts->has('1505')) {
            JournalDetail::create([
                'journal_id' => $journal9->id,
                'account_id' => $accounts['5107']->id,
                'debit' => 5000000,
                'credit' => 0,
            ]);
            JournalDetail::create([
                'journal_id' => $journal9->id,
                'account_id' => $accounts['1505']->id,
                'debit' => 0,
                'credit' => 5000000,
            ]);
        }

        $this->command->info('✅ Laporan Posisi Keuangan data seeded successfully!');
        $this->command->info('📊 Created ' . Journal::count() . ' journal entries with sample transactions');
    }
}
