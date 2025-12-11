<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'code' => 'MFK',
                'name' => 'PT MITRA FAJAR KENCANA',
                'logo' => 'images/logo_wb.png',
                'description' => 'Perusahaan konstruksi dan perdagangan material bangunan',
                'address' => 'Jalan Dahlia No.30, Kel. Suka Jadi, Pekanbaru, Riau',
                'phone' => '082285993694',
                'email' => 'ptmitrafajarkencana@gmail.com',
                'is_active' => true
            ],
            [
                'code' => 'HS',
                'name' => 'PT. Hasama Sakti',
                'logo' => null,
                'description' => 'PT ini bergerak dalam bidang konstruksi bangunan jalan',
                'address' => 'Pekanbaru, Riau',
                'phone' => null,
                'email' => null,
                'is_active' => true
            ],
            [
                'code' => 'HCK',
                'name' => 'PT. Hasta Citra Konstruksi',
                'logo' => null,
                'description' => 'PT ini bergerak dalam bidang pembangunan gedung',
                'address' => 'Pekanbaru, Riau',
                'phone' => null,
                'email' => null,
                'is_active' => true
            ],
            [
                'code' => 'MPA',
                'name' => 'CV. Mitra Persada Abadi',
                'logo' => null,
                'description' => 'CV ini bergerak di bidang perdagangan alat pertanian, reparasi kendaraan dan penyewaan',
                'address' => 'Pekanbaru, Riau',
                'phone' => null,
                'email' => null,
                'is_active' => true
            ],
            [
                'code' => 'MFS',
                'name' => 'CV. Mitra Fajar Sejahtera',
                'logo' => null,
                'description' => 'CV ini bergerak di bidang jasa penyewaan alat berat',
                'address' => 'Pekanbaru, Riau',
                'phone' => null,
                'email' => null,
                'is_active' => true
            ],
            [
                'code' => 'KA',
                'name' => 'CV. Karya Avatar',
                'logo' => null,
                'description' => 'CV ini menjalankan usaha dalam bidang pengangkutan dan perdagangan, jasa serta industri',
                'address' => 'Pekanbaru, Riau',
                'phone' => null,
                'email' => null,
                'is_active' => true
            ],
            [
                'code' => 'WT',
                'name' => 'CV. Winetou',
                'logo' => 'images/logo_wt.png',
                'description' => 'CV ini bergerak di bidang jasa penyewaan alat berat',
                'address' => 'Pekanbaru, Riau',
                'phone' => null,
                'email' => null,
                'is_active' => true
            ],
            [
                'code' => 'PL',
                'name' => 'CV. Prasetyo Lestari',
                'logo' => null,
                'description' => 'CV ini bergerak di bidang arsitektur dan real estate',
                'address' => 'Pekanbaru, Riau',
                'phone' => null,
                'email' => null,
                'is_active' => true
            ]
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }

        $this->command->info('✓ Companies seeded successfully!');
    }
}
