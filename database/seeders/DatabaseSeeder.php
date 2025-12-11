<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AccountCategory;
use App\Models\Account;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed users only if none exist
        if (User::count() === 0) {
            $this->call(UserSeeder::class);
        }

        // Create account categories and accounts
        $this->call([
            AccountCategorySeeder::class,
            AccountSeeder::class,
        ]);
        
        // Seed companies
        $this->call(CompanySeeder::class);
        
        // Seed suppliers and barang
        $this->call([
            SupplierSeeder::class,
            BarangSeeder::class,
        ]);
        
        // Seed assets and journals with test data
        $this->call([
            AssetSeeder::class,
            JournalSeeder::class,
        ]);
    }
}
