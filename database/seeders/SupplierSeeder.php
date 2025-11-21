<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            ['kode_supplier' => 'SUP001', 'nama_supplier' => 'PT Sentosa Jaya'],
            ['kode_supplier' => 'SUP002', 'nama_supplier' => 'CV Maju Bersama'],
            ['kode_supplier' => 'SUP003', 'nama_supplier' => 'PT Karya Mandiri'],
            ['kode_supplier' => 'SUP004', 'nama_supplier' => 'UD Berkah Sejahtera'],
            ['kode_supplier' => 'SUP005', 'nama_supplier' => 'PT Indo Makmur'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        echo "Created " . count($suppliers) . " suppliers.\n";
    }
}
