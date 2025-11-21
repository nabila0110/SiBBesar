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
            [
                'code' => '1',
                'name' => 'AKTIVA',
                'type' => 'asset',
                'description' => 'Kategori untuk semua akun aktiva/aset',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'code' => '2',
                'name' => 'KEWAJIBAN',
                'type' => 'liability',
                'description' => 'Kategori untuk semua akun kewajiban/hutang',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'code' => '3',
                'name' => 'EKUITAS',
                'type' => 'equity',
                'description' => 'Kategori untuk akun modal dan laba',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'code' => '4',
                'name' => 'PENDAPATAN',
                'type' => 'revenue',
                'description' => 'Kategori untuk semua akun pendapatan',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'code' => '5',
                'name' => 'BEBAN',
                'type' => 'expense',
                'description' => 'Kategori untuk semua akun beban/biaya',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::table('account_categories')->insert($categories);
        
        $this->command->info('Created ' . count($categories) . ' account categories.');
    }
}
