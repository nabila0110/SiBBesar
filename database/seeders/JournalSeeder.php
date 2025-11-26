<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Journal;
use App\Models\Account;

class JournalSeeder extends Seeder
{
    public function run()
    {
        // Get specific accounts for double entry
        $bebanGajiAccount = Account::where('name', 'LIKE', '%Gaji%')->where('type', 'expense')->first();
        $pendapatanJasaAccount = Account::where('name', 'LIKE', '%Jasa%')->where('type', 'revenue')->first();
        $bebanMaterialAccount = Account::where('name', 'LIKE', '%Material%')->where('type', 'expense')->first();
        
        // Get required accounts for double entry pairing
        $kasAccount = Account::whereHas('category', function($q) {
            $q->where('type', 'asset');
        })->where('code', '1100')->first();
        
        $piutangAccount = Account::whereHas('category', function($q) {
            $q->where('type', 'asset');
        })->where('code', '1300')->first();
        
        $hutangAccount = Account::whereHas('category', function($q) {
            $q->where('type', 'liability');
        })->where('code', '1100')->first();
        
        // If no accounts found, abort
        if (!$bebanGajiAccount || !$pendapatanJasaAccount || !$kasAccount || !$piutangAccount || !$hutangAccount) {
            $this->command->warn('Required accounts not found. Please run AccountSeeder first.');
            return;
        }

        $this->command->info('Creating journals with double entry system...');

        // CONTOH 1: Pendapatan Jasa - Lunas (IN + LUNAS → Kas bertambah)
        $this->createDoubleEntryJournal(
            '2025-11-20',
            'Pembayaran Proyek Paving Jalan Komplek',
            1,
            'paket',
            30000000,
            true,
            'KOMPLEK ELITE',
            'CV-001',
            'in',
            'lunas',
            $pendapatanJasaAccount->id,
            $kasAccount->id
        );

        // CONTOH 2: Beban Gaji - Tidak Lunas (OUT + TIDAK LUNAS → Hutang bertambah)
        $this->createDoubleEntryJournal(
            '2025-11-21',
            'Upah Paving dan Finishing',
            20,
            'orang',
            1500000,
            false,
            'KOMPLEK ELITE',
            'UPAH-001',
            'out',
            'tidak_lunas',
            $bebanGajiAccount->id,
            $hutangAccount->id
        );

        // CONTOH 3: Pendapatan Jasa - Tidak Lunas (IN + TIDAK LUNAS → Piutang bertambah)
        $this->createDoubleEntryJournal(
            '2025-11-22',
            'Pendapatan Jasa Konsultasi',
            1,
            'paket',
            50000000,
            true,
            'GEDUNG PERKANTORAN',
            'INV-001',
            'in',
            'tidak_lunas',
            $pendapatanJasaAccount->id,
            $piutangAccount->id
        );

        // CONTOH 4: Beban Material - Lunas (OUT + LUNAS → Kas berkurang)
        $this->createDoubleEntryJournal(
            '2025-11-23',
            'Pembelian Material Paving Block',
            5000,
            'pcs',
            12500,
            false,
            'KOMPLEK ELITE',
            'PO-001',
            'out',
            'lunas',
            $bebanMaterialAccount->id,
            $kasAccount->id
        );

        // CONTOH 5: Beban Gaji - Lunas (OUT + LUNAS → Kas berkurang)
        $this->createDoubleEntryJournal(
            '2025-11-24',
            'Gaji Karyawan November',
            15,
            'orang',
            3500000,
            false,
            'OPERASIONAL',
            'PAY-001',
            'out',
            'lunas',
            $bebanGajiAccount->id,
            $kasAccount->id
        );

        // CONTOH 6: Pendapatan - Lunas lagi
        $this->createDoubleEntryJournal(
            '2025-11-25',
            'Pelunasan Proyek Sebelumnya',
            1,
            'paket',
            75000000,
            true,
            'MALL SENTOSA',
            'INV-002',
            'in',
            'lunas',
            $pendapatanJasaAccount->id,
            $kasAccount->id
        );

        $this->command->info('Successfully created 6 example journals with double entry!');
    }

    /**
     * Helper method untuk create double entry journal
     */
    private function createDoubleEntryJournal(
        $date,
        $item,
        $qty,
        $satuan,
        $price,
        $tax,
        $project,
        $nota,
        $type,
        $paymentStatus,
        $mainAccountId,
        $pairedAccountId
    ) {
        $total = $qty * $price;
        $ppnAmount = $tax ? ($total * 0.11) : 0;
        $finalTotal = $total + $ppnAmount;

        // Journal 1: Main (User pilih)
        $journal1 = Journal::create([
            'transaction_date' => $date,
            'item' => $item,
            'quantity' => $qty,
            'satuan' => $satuan,
            'price' => $price,
            'total' => $total,
            'tax' => $tax,
            'ppn_amount' => $ppnAmount,
            'final_total' => $finalTotal,
            'debit' => $type === 'out' ? $finalTotal : 0,
            'kredit' => $type === 'in' ? $finalTotal : 0,
            'is_paired' => false,
            'project' => $project,
            'nota' => $nota,
            'type' => $type,
            'payment_status' => $paymentStatus,
            'account_id' => $mainAccountId,
            'reference' => 'JRN/2025/11/' . str_pad(Journal::count() + 1, 4, '0', STR_PAD_LEFT),
            'status' => 'posted',
            'created_by' => 1,
        ]);

        // Journal 2: Paired (Otomatis)
        $journal2 = Journal::create([
            'transaction_date' => $date,
            'item' => $item . ' (Pasangan)',
            'quantity' => $qty,
            'satuan' => $satuan,
            'price' => $price,
            'total' => $total,
            'tax' => $tax,
            'ppn_amount' => $ppnAmount,
            'final_total' => $finalTotal,
            'debit' => $type === 'in' ? $finalTotal : 0,
            'kredit' => $type === 'out' ? $finalTotal : 0,
            'is_paired' => true,
            'paired_journal_id' => $journal1->id,
            'project' => $project,
            'nota' => $nota,
            'type' => $type,
            'payment_status' => $paymentStatus,
            'account_id' => $pairedAccountId,
            'reference' => $journal1->reference,
            'status' => 'posted',
            'created_by' => 1,
        ]);

        // Update journal1 with paired_journal_id
        $journal1->update(['paired_journal_id' => $journal2->id]);
    }
}
