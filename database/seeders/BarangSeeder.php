<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Supplier;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = Supplier::all();

        $barangs = [
            ['nama' => 'Laptop ASUS ROG', 'harga' => 15000000, 'stok' => 10, 'supplier_id' => $suppliers[0]->id ?? null],
            ['nama' => 'Mouse Logitech G502', 'harga' => 750000, 'stok' => 25, 'supplier_id' => $suppliers[0]->id ?? null],
            ['nama' => 'Keyboard Mechanical', 'harga' => 1200000, 'stok' => 15, 'supplier_id' => $suppliers[1]->id ?? null],
            ['nama' => 'Monitor LG 27 inch', 'harga' => 3500000, 'stok' => 8, 'supplier_id' => $suppliers[1]->id ?? null],
            ['nama' => 'Headset Gaming', 'harga' => 850000, 'stok' => 20, 'supplier_id' => $suppliers[2]->id ?? null],
            ['nama' => 'Webcam Logitech C920', 'harga' => 1500000, 'stok' => 12, 'supplier_id' => $suppliers[2]->id ?? null],
            ['nama' => 'SSD Samsung 1TB', 'harga' => 1800000, 'stok' => 30, 'supplier_id' => $suppliers[3]->id ?? null],
            ['nama' => 'RAM Corsair 16GB', 'harga' => 1200000, 'stok' => 40, 'supplier_id' => $suppliers[3]->id ?? null],
            ['nama' => 'Printer Epson L3110', 'harga' => 2100000, 'stok' => 5, 'supplier_id' => $suppliers[4]->id ?? null],
            ['nama' => 'UPS APC 1200VA', 'harga' => 2500000, 'stok' => 7, 'supplier_id' => $suppliers[5]->id ?? null],
            ['nama' => 'Router TP-Link', 'harga' => 450000, 'stok' => 18, 'supplier_id' => $suppliers[6]->id ?? null],
            ['nama' => 'Switch Cisco 24 Port', 'harga' => 5500000, 'stok' => 3, 'supplier_id' => null],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }

        echo "Created " . count($barangs) . " barang items.\n";
    }
}

