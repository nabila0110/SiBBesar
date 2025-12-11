<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Journal;
use App\Models\Account;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class JournalSeeder extends Seeder
{
    public function run()
    {
        // Keep things deterministic-ish when desired
        //srand(12345);

        // Get all active companies
        $companies = Company::where('is_active', true)->get();
        if ($companies->isEmpty()) {
            $this->command->error('No active companies found. Please seed companies first.');
            return;
        }
        
        // Get main company (MFK) for opening balance
        $mainCompany = $companies->where('code', 'MFK')->first() ?? $companies->first();

        // STEP 1: Buat jurnal saldo awal untuk Modal Pemilik
        $modalPemilik = Account::where('name', 'like', '%modal%pemilik%')->first();
        $kasAccount = Account::where('name', 'like', '%kas%')->first();
        
        if ($modalPemilik && $kasAccount) {
            $modalAwal = 100000000; // 100 juta modal awal
            $tanggalAwal = '2024-01-01';
            
            // Jurnal: Debit Kas, Kredit Modal Pemilik (setoran modal)
            $mainJurnal = Journal::create([
                'transaction_date' => $tanggalAwal,
                'item' => 'Setoran Modal Awal Pemilik',
                'quantity' => 1,
                'satuan' => 'paket',
                'price' => $modalAwal,
                'total' => $modalAwal,
                'tax' => false,
                'ppn_amount' => 0,
                'final_total' => $modalAwal,
                'debit' => $modalAwal,  // Debit Kas
                'kredit' => 0,
                'project' => 'OPENING',
                'ket' => 'Saldo awal modal pemilik',
                'nota' => 'OPENING-BALANCE',
                'type' => 'in',
                'payment_status' => 'lunas',
                'account_id' => $kasAccount->id,
                'reference' => 'OPENING-001',
                'status' => 'posted',
                'created_by' => 1,
                'updated_by' => 1,
                'is_paired' => false,
                'company_id' => $mainCompany->id,
            ]);
            
            $pairedJurnal = Journal::create([
                'transaction_date' => $tanggalAwal,
                'item' => 'Setoran Modal Awal Pemilik (Pasangan)',
                'quantity' => 1,
                'satuan' => 'paket',
                'price' => $modalAwal,
                'total' => $modalAwal,
                'tax' => false,
                'ppn_amount' => 0,
                'final_total' => $modalAwal,
                'debit' => 0,
                'kredit' => $modalAwal,  // Kredit Modal Pemilik
                'project' => 'OPENING',
                'ket' => 'Saldo awal modal pemilik',
                'nota' => 'OPENING-BALANCE',
                'type' => 'in',
                'payment_status' => 'lunas',
                'account_id' => $modalPemilik->id,
                'reference' => 'OPENING-001',
                'status' => 'posted',
                'created_by' => 1,
                'updated_by' => 1,
                'is_paired' => true,
                'paired_journal_id' => $mainJurnal->id,
                'company_id' => $mainCompany->id,
            ]);
            
            $mainJurnal->paired_journal_id = $pairedJurnal->id;
            $mainJurnal->save();
            
            $this->command->info('Created opening balance for Modal Pemilik: ' . number_format($modalAwal, 0, ',', '.'));
        }
        
        // STEP 2: Buat jurnal operasional untuk akun lainnya
        // Ambil semua akun kecuali Laba Ditahan dan Modal Pemilik
        // (karena keduanya adalah akun equity yang tidak untuk transaksi operasional)
        $accounts = Account::where('is_active', true)
            ->where('name', 'NOT LIKE', '%laba%ditahan%')
            ->where('name', 'NOT LIKE', '%retained%earning%')
            ->where('name', 'NOT LIKE', '%modal%pemilik%')
            ->where('name', 'NOT LIKE', '%owner%equity%')
            ->get();
        if ($accounts->count() < 2) {
            $this->command->info('Not enough accounts to seed journals. Please seed accounts first.');
            return;
        }

        $start = Carbon::create(2024, 1, 1);
        $end = Carbon::create(2025, 12, 31);

        // Build some lookup accounts for kas/piutang/hutang fallbacks
        $kasAccount = Account::where('name', 'like', '%kas%')->first();
        $piutangAccount = Account::where('name', 'like', '%piutang%')->first();
        $hutangAccount = Account::where('name', 'like', '%utang%')->orWhere('name', 'like', '%hutang%')->first();

        $months = [];
        $cursor = $start->copy();
        while ($cursor->lessThanOrEqualTo($end)) {
            $months[] = $cursor->copy();
            $cursor->addMonth();
        }

        $this->command->info('Seeding journals for ' . count($months) . ' months and ' . $accounts->count() . ' accounts...');

        // Daftar item transaksi konstruksi yang realistis
        $itemsKonstruksi = [
            // Material Konstruksi
            'Pembelian kawat likat',
            'Pembelian Granit',
            'Pembelian cat',
            'Pembelian Material Listrik',
            'Pembelian pasir',
            'Pembelian Material',
            'Pembelian meterial Pipa Kota Asri Jaya',
            'Rembes pak dedi Material',
            
            // DP dan Pekerjaan
            'DP Pekerjaan listrik',
            'Tambahan DP Listrik',
            
            // Transport dan BBM
            'Uang minyak anggota heri Pipa Kota Minggu ini',
            'Pembelian bensin Veloz Pipa Kota',
            'BBM Solar PT DEALOVA YUDHA PRATAMA',
            
            // Pembayaran dan Administrasi
            'Pembayaran Pengiriman berkas oki baung ke kantor',
            'Rembes Bang iki FC Berkas Pipa Kota',
            'Rembes bang iki makan minum dinas pipa kota',
            'Rembes Bang iki jilid adendum Pipa Kota',
            
            // Bon dan Supplier
            'Bon JT Multi Cipta Sanjaya (Seng dan Atap)',
            'Bon JT Multi Cipta Sanjaya (Semen 50 Sak)',
            
            // Pinjaman
            'Pinjaman tukang pagar (Pak Donal)',
            'Pinjaman Bg Beni Plafon',
            'Pinjamn Mas Gendut Paving',
            'Pinjaman Mas Heri Pipa Kota',
            
            // Pengambilan dan Utilitas
            'Pengambilan Mas Ton',
            'Listrik Bang Yofi',
        ];
        
        // Nama proyek
        $namaProyek = ['Rohul', 'Damailanggeng', 'Pipa Kota', 'Mall', 'Gedung'];
        
        // Daftar nama orang untuk nota
        $namaNota = [
            'Budi Santoso', 'Siti Aminah', 'Agus Wijaya', 'Dewi Lestari', 'Eko Prasetyo',
            'Fitri Handayani', 'Gunawan', 'Heni Kartika', 'Indra Kusuma', 'Joko Susilo',
            'Rina Marlina', 'Bambang Suryadi', 'Lina Safitri', 'Hendra Gunawan', 'Maya Sari',
            'Dedi Setiawan', 'Wati Susilawati', 'Ahmad Fauzi', 'Sri Wahyuni', 'Andi Nugroho',
            'Pak Donal', 'Bang Yofi', 'Mas Gendut', 'Mas Ton', 'Mas Heri', 'Bang Iki', 'Bg Beni'
        ];

        DB::transaction(function () use ($accounts, $months, $kasAccount, $piutangAccount, $hutangAccount, $namaNota, $companies, $itemsKonstruksi, $namaProyek) {
            foreach ($months as $month) {
                foreach ($accounts as $account) {
                    // 30% chance to create a transaction for this account in this month
                    if (random_int(1, 100) > 30) {
                        continue;
                    }
                    
                    // Randomly assign a company to this transaction
                    $company = $companies->random();

                    $type = random_int(0, 1) ? 'in' : 'out';
                    // More likely to be lunas for cash transactions
                    $payment_status = (random_int(1, 100) <= 75) ? 'lunas' : 'tidak_lunas';

                    // Nominal LEBIH BESAR untuk perusahaan sungguhan
                    $base = 5000000;  // 5 juta
                    $variance = ($account->id % 5 + 1) * 10000000; // 10-50 juta variasi
                    $amount = random_int($base, $base + $variance);
                    // Slight monthly smoothing
                    $amount = (int) round($amount * (0.8 + mt_rand() / mt_getrandmax() * 0.6));

                    // Determine paired account
                    $paired = null;
                    if ($type === 'in') {
                        $paired = ($payment_status === 'lunas') ? $kasAccount : $piutangAccount;
                    } else {
                        $paired = ($payment_status === 'lunas') ? $kasAccount : $hutangAccount;
                    }

                    // Fallback: pick a different account if no kas/piutang/hutang found
                    if (! $paired || $paired->id === $account->id) {
                        $paired = $accounts->where('id', '!=', $account->id)->random();
                    }

                    // Build a realistic construction item with project name
                    $baseItem = $itemsKonstruksi[array_rand($itemsKonstruksi)];
                    $project = $namaProyek[array_rand($namaProyek)];
                    $item = $baseItem . ' ' . $project;
                    
                    // Nota berisi nama orang yang melakukan transaksi
                    $notaNama = $namaNota[array_rand($namaNota)];

                    // PERBAIKAN: Logika debit/kredit yang BENAR
                    // Untuk IN (uang masuk):
                    //   - Main (revenue account) = KREDIT
                    //   - Paired (kas/piutang) = DEBIT
                    // Untuk OUT (uang keluar):
                    //   - Main (expense account) = DEBIT
                    //   - Paired (kas/hutang) = KREDIT
                    
                    $mainData = [
                        'transaction_date' => $month->format('Y-m-d'),
                        'item' => $item,
                        'quantity' => 1,
                        'satuan' => 'paket',
                        'price' => $amount,
                        'total' => $amount,
                        'tax' => false,
                        'ppn_amount' => 0,
                        'final_total' => $amount,
                        'debit' => $type === 'out' ? $amount : 0,  // OUT: debit expense
                        'kredit' => $type === 'in' ? $amount : 0,  // IN: kredit revenue
                        'project' => 'PROJECT-' . $project,
                        'ket' => 'Transaksi otomatis',
                        'nota' => $namaNota[array_rand($namaNota)],
                        'type' => $type,
                        'payment_status' => $payment_status,
                        'account_id' => $account->id,
                        'reference' => Journal::generateJournalNo(),
                        'status' => 'posted',
                        'created_by' => 1,
                        'updated_by' => 1,
                        'is_paired' => false,
                        'company_id' => $company->id,
                    ];

                    $main = Journal::create($mainData);

                    // PERBAIKAN: Paired journal harus KEBALIKAN dari main
                    $pairedData = [
                        'transaction_date' => $mainData['transaction_date'],
                        'item' => $item . ' (Pasangan)',
                        'quantity' => $mainData['quantity'],
                        'satuan' => $mainData['satuan'],
                        'price' => $mainData['price'],
                        'total' => $mainData['total'],
                        'tax' => $mainData['tax'],
                        'ppn_amount' => $mainData['ppn_amount'],
                        'final_total' => $mainData['final_total'],
                        'debit' => $type === 'in' ? $amount : 0,   // IN: debit kas/piutang
                        'kredit' => $type === 'out' ? $amount : 0, // OUT: kredit kas/hutang
                        'project' => $mainData['project'],
                        'ket' => $mainData['ket'],
                        'nota' => $mainData['nota'],
                        'type' => $mainData['type'],
                        'payment_status' => $mainData['payment_status'],
                        'account_id' => $paired->id,
                        'reference' => $mainData['reference'], // Sama dengan main
                        'status' => 'posted',
                        'created_by' => 1,
                        'updated_by' => 1,
                        'is_paired' => true,
                        'paired_journal_id' => $main->id,
                        'company_id' => $company->id,
                    ];

                    $pairedJournal = Journal::create($pairedData);

                    // Link main to paired
                    $main->paired_journal_id = $pairedJournal->id;
                    $main->save();
                }
            }
        });

        $this->command->info('Journal seeding complete.');
    }
}
