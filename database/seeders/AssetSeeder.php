<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssetSeeder extends Seeder
{
    public function run()
    {
        // Skip if assets already exist
        if (DB::table('assets')->count() > 0) {
            $this->command->info('Assets already exist, skipping...');
            return;
        }
        
        // Get account IDs - use LIKE to find accounts
        $peralatanAccount = Account::where('type', 'asset')->where('name', 'LIKE', '%Peralatan%')->first();
        $kendaraanAccount = Account::where('type', 'asset')->where('name', 'LIKE', '%Kendaraan%')->first();
        $gedungAccount = Account::where('type', 'asset')->where('name', 'LIKE', '%Bangunan%')->first();
        
        // Fallback - use any asset account
        if (!$peralatanAccount) {
            $peralatanAccount = Account::where('type', 'asset')->first();
        }
        if (!$kendaraanAccount) {
            $kendaraanAccount = Account::where('type', 'asset')->skip(1)->first() ?? $peralatanAccount;
        }
        if (!$gedungAccount) {
            $gedungAccount = Account::where('type', 'asset')->skip(2)->first() ?? $peralatanAccount;
        }
        
        $now = Carbon::now();
        
        $assets = [
            // Peralatan
            [
                'account_id' => $peralatanAccount->id,
                'asset_name' => 'Excavator Komatsu PC200',
                'description' => 'Excavator untuk proyek konstruksi dan galian',
                'purchase_date' => '2020-03-15',
                'purchase_price' => 850000000,
                'depreciation_rate' => 20.00,
                'location' => 'Workshop Utama',
                'condition' => 'Baik',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'account_id' => $peralatanAccount->id,
                'asset_name' => 'Mesin Las SMAW 400A',
                'description' => 'Mesin las untuk penyambungan pipa',
                'purchase_date' => '2021-06-20',
                'purchase_price' => 12500000,
                'depreciation_rate' => 25.00,
                'location' => 'Workshop',
                'condition' => 'Baik',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'account_id' => $peralatanAccount->id,
                'asset_name' => 'Mesin Bor Tanah',
                'description' => 'Bor untuk instalasi pipa bawah tanah',
                'purchase_date' => '2021-08-10',
                'purchase_price' => 25000000,
                'depreciation_rate' => 20.00,
                'location' => 'Gudang Alat',
                'condition' => 'Baik',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'account_id' => $peralatanAccount->id,
                'asset_name' => 'Generator Diesel 20KVA',
                'description' => 'Generator untuk supply listrik proyek',
                'purchase_date' => '2022-02-05',
                'purchase_price' => 18000000,
                'depreciation_rate' => 20.00,
                'location' => 'Workshop',
                'condition' => 'Sangat Baik',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'account_id' => $peralatanAccount->id,
                'asset_name' => 'Pompa Air Submersible',
                'description' => 'Pompa untuk drainase dan dewatering',
                'purchase_date' => '2022-05-15',
                'purchase_price' => 8500000,
                'depreciation_rate' => 25.00,
                'location' => 'Gudang',
                'condition' => 'Baik',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'account_id' => $peralatanAccount->id,
                'asset_name' => 'Compressor Udara 150PSI',
                'description' => 'Kompresor untuk pneumatic tools',
                'purchase_date' => '2022-09-20',
                'purchase_price' => 15000000,
                'depreciation_rate' => 20.00,
                'location' => 'Workshop',
                'condition' => 'Baik',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // Kendaraan
            [
                'account_id' => $kendaraanAccount->id,
                'asset_name' => 'Dump Truck Hino Ranger',
                'description' => 'Dump truck untuk angkut material',
                'purchase_date' => '2019-11-10',
                'purchase_price' => 485000000,
                'depreciation_rate' => 20.00,
                'location' => 'Pool Kendaraan',
                'condition' => 'Baik',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'account_id' => $kendaraanAccount->id,
                'asset_name' => 'Pickup Toyota Hilux',
                'description' => 'Mobil operasional dan survey proyek',
                'purchase_date' => '2020-07-15',
                'purchase_price' => 385000000,
                'depreciation_rate' => 20.00,
                'location' => 'Kantor',
                'condition' => 'Sangat Baik',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'account_id' => $kendaraanAccount->id,
                'asset_name' => 'Truk Fuso Fighter',
                'description' => 'Truk untuk mobilisasi alat dan material',
                'purchase_date' => '2021-03-22',
                'purchase_price' => 520000000,
                'depreciation_rate' => 20.00,
                'location' => 'Pool Kendaraan',
                'condition' => 'Baik',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'account_id' => $kendaraanAccount->id,
                'asset_name' => 'Minibus Isuzu Elf',
                'description' => 'Kendaraan untuk transportasi karyawan',
                'purchase_date' => '2021-08-30',
                'purchase_price' => 280000000,
                'depreciation_rate' => 20.00,
                'location' => 'Kantor',
                'condition' => 'Baik',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // Gedung
            [
                'account_id' => $gedungAccount->id,
                'asset_name' => 'Gedung Kantor Pusat',
                'description' => 'Gedung kantor 3 lantai',
                'purchase_date' => '2018-05-20',
                'purchase_price' => 1850000000,
                'depreciation_rate' => 5.00,
                'location' => 'Jl. Raya Ind.',
                'condition' => 'Sangat Baik',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'account_id' => $gedungAccount->id,
                'asset_name' => 'Gudang Workshop',
                'description' => 'Gudang dan workshop peralatan',
                'purchase_date' => '2019-02-10',
                'purchase_price' => 650000000,
                'depreciation_rate' => 5.00,
                'location' => 'Jl. Industri',
                'condition' => 'Baik',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'account_id' => $gedungAccount->id,
                'asset_name' => 'Mess Karyawan',
                'description' => 'Penginapan untuk karyawan proyek',
                'purchase_date' => '2020-09-15',
                'purchase_price' => 420000000,
                'depreciation_rate' => 5.00,
                'location' => 'Area Kantor',
                'condition' => 'Baik',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::table('assets')->insert($assets);
        
        $this->command->info('Created ' . count($assets) . ' assets.');
    }
}
