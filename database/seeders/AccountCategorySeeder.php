<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountCategorySeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        
        $categories = [
            // ASSET CATEGORIES
            [
                'code' => '1-1',
                'name' => 'Aset Lancar',
                'type' => 'asset',
                'description' => 'Aset yang mudah dicairkan dalam waktu kurang dari 1 tahun (Kas, Bank, Piutang)',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'code' => '1-2',
                'name' => 'Aset Tetap',
                'type' => 'asset',
                'description' => 'Aset jangka panjang seperti peralatan, kendaraan, gedung',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'code' => '1-3',
                'name' => 'Aset Lainnya',
                'type' => 'asset',
                'description' => 'Aset tidak berwujud dan investasi jangka panjang',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // LIABILITY CATEGORIES
            [
                'code' => '2-1',
                'name' => 'Kewajiban Lancar',
                'type' => 'liability',
                'description' => 'Hutang jangka pendek yang harus dibayar dalam 1 tahun',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'code' => '2-2',
                'name' => 'Kewajiban Jangka Panjang',
                'type' => 'liability',
                'description' => 'Hutang jangka panjang lebih dari 1 tahun',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // EQUITY CATEGORIES
            [
                'code' => '3-1',
                'name' => 'Modal',
                'type' => 'equity',
                'description' => 'Modal pemilik dan laba ditahan',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // REVENUE CATEGORIES
            [
                'code' => '4-1',
                'name' => 'Pendapatan Usaha',
                'type' => 'revenue',
                'description' => 'Pendapatan dari kegiatan operasional utama',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'code' => '4-2',
                'name' => 'Pendapatan Lain-lain',
                'type' => 'revenue',
                'description' => 'Pendapatan di luar kegiatan operasional',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // EXPENSE CATEGORIES
            [
                'code' => '5-1',
                'name' => 'Beban Operasional',
                'type' => 'expense',
                'description' => 'Beban untuk menjalankan kegiatan usaha (Gaji, Sewa, Listrik)',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'code' => '5-2',
                'name' => 'Beban Penyusutan',
                'type' => 'expense',
                'description' => 'Penyusutan aset tetap',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'code' => '5-3',
                'name' => 'Beban Lain-lain',
                'type' => 'expense',
                'description' => 'Beban di luar kegiatan operasional',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        // Use updateOrInsert to avoid duplicates
        foreach ($categories as $category) {
            DB::table('account_categories')->updateOrInsert(
                ['code' => $category['code']], // Match by code
                $category // Update with these values
            );
        }
        
        $this->command->info('Created/Updated ' . count($categories) . ' account categories.');
    }
}
