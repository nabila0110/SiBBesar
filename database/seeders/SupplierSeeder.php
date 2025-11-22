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
            ['nama_supplier' => 'PT Sentosa Jaya', 'email' => 'sentosa@example.com', 'alamat' => 'Jakarta Pusat', 'telepon' => '021-12345678'],
            ['nama_supplier' => 'CV Maju Bersama', 'email' => 'maju@example.com', 'alamat' => 'Bandung', 'telepon' => '022-87654321'],
            ['nama_supplier' => 'PT Karya Mandiri', 'email' => 'karya@example.com', 'alamat' => 'Surabaya', 'telepon' => '031-11223344'],
            ['nama_supplier' => 'UD Berkah Sejahtera', 'email' => 'berkah@example.com', 'alamat' => 'Semarang', 'telepon' => '024-55667788'],
            ['nama_supplier' => 'PT Indo Makmur', 'email' => 'indo@example.com', 'alamat' => 'Yogyakarta', 'telepon' => '0274-99887766'],
            ['nama_supplier' => 'CV Abadi Jaya', 'email' => 'abadi@example.com', 'alamat' => 'Malang', 'telepon' => '0341-22334455'],
            ['nama_supplier' => 'PT Sumber Rejeki', 'email' => 'sumber@example.com', 'alamat' => 'Solo', 'telepon' => '0271-66778899'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        echo "Created " . count($suppliers) . " suppliers.\n";
    }
}
