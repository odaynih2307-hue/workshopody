<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barang = [
            [
                'id_barang' => 'BRG-001',
                'nama_barang' => 'Laptop Lenovo',
                'harga' => 7500000,
            ],
            [
                'id_barang' => 'BRG-002',
                'nama_barang' => 'Mouse Wireless',
                'harga' => 150000,
            ],
            [
                'id_barang' => 'BRG-003',
                'nama_barang' => 'Keyboard Mechanical',
                'harga' => 850000,
            ],
            [
                'id_barang' => 'BRG-004',
                'nama_barang' => 'Monitor LG 24 inch',
                'harga' => 2500000,
            ],
            [
                'id_barang' => 'BRG-005',
                'nama_barang' => 'Headphone Sony',
                'harga' => 1200000,
            ],
            [
                'id_barang' => 'BRG-006',
                'nama_barang' => 'Webcam HD',
                'harga' => 500000,
            ],
            [
                'id_barang' => 'BRG-007',
                'nama_barang' => 'SSD 512GB',
                'harga' => 800000,
            ],
            [
                'id_barang' => 'BRG-008',
                'nama_barang' => 'RAM DDR4 16GB',
                'harga' => 900000,
            ],
        ];

        foreach ($barang as $item) {
            Barang::create($item);
        }
    }
}

