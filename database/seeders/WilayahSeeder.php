<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Provinsi
        $provinsiData = [
            'Jawa Barat' => [
                'Bandung' => [
                    'Babakan Ciparay' => ['Cibeureum', 'Ciburial', 'Cigondewah Kaler'],
                    'Astana Anyar' => ['Astana Anyar', 'Babakan'],
                    'Bandung Kidul' => ['Antimastra', 'Jajang']
                ],
                'Bogor' => [
                    'Bogor Utara' => ['Kayu Manis', 'Sempu', 'Semplak'],
                    'Bogor Timur' => ['Cikaret', 'Cimahpar']
                ]
            ],
            'Jawa Tengah' => [
                'Semarang' => [
                    'Gajahmungkur' => ['Bendan Ngisor', 'Bendan Tengah', 'Gajahmungkur'],
                    'Semarang Utara' => ['Kuripan', 'Pandansari']
                ],
                'Surakarta' => [
                    'Laweyan' => ['Gajahan', 'Kampung Anyar', 'Notoharjan'],
                    'Pasar Kliwon' => ['Joyosuran', 'Sengkolet']
                ]
            ],
            'Jawa Timur' => [
                'Surabaya' => [
                    'Surabaya Utara' => ['Alang-alang', 'Ampel', 'Asemrowo'],
                    'Surabaya Pusat' => ['Kedungdoro', 'Ketintang']
                ],
                'Malang' => [
                    'Malang Selatan' => ['Blimbing', 'Lowokwaru'],
                    'Malang Utara' => ['Bareng', 'Kedungkandang']
                ]
            ],
            'Sumatera Utara' => [
                'Medan' => [
                    'Medan Merdeka' => ['Kesawan', 'Petisah', 'Pandang Hulu'],
                    'Medan Timur' => ['Aksara', 'Glugur']
                ],
                'Binjai' => [
                    'Binjai Utara' => ['Ambora', 'Binjai'],
                    'Binjai Kota' => ['Binjai Kuala', 'Binjai Hulu']
                ]
            ],
            'Sumatera Selatan' => [
                'Palembang' => [
                    'Ilir Timur I' => ['1 Ulu', '2 Ulu', '3 Ulu'],
                    'Ilir Barat I' => ['7 Ulu', '8 Ulu']
                ],
                'Lubuklinggau' => [
                    'Lubuklinggau Utara' => ['Air Kumbai', 'Lubuklinggau'],
                    'Lubuklinggau Selatan' => ['Rantau Panjang', 'Tanjung Raja']
                ]
            ],
            'DKI Jakarta' => [
                'Jakarta Pusat' => [
                    'Menteng' => ['Cempaka Putih', 'Menteng', 'Pegangsaan'],
                    'Tanah Abang' => ['Bendungan', 'Kramat', 'Tanah Abang']
                ],
                'Jakarta Selatan' => [
                    'Tebet' => ['Bidara Cina', 'Bukit Duri', 'Tebet'],
                    'Sétia Budhi' => ['Cipete', 'Lebak Bulus']
                ]
            ],
            'Bali' => [
                'Denpasar' => [
                    'Denpasar Utara' => ['Kampial', 'Pemecutan', 'Sumerta'],
                    'Denpasar Barat' => ['Jimbaran', 'Pemogan']
                ],
                'Badung' => [
                    'Kuta' => ['Kuta', 'Legian'],
                    'Ubud' => ['Penggosekan', 'Ubud']
                ]
            ]
        ];

        foreach ($provinsiData as $provinsiName => $kotaArray) {
            // Create Provinsi
            $provinsi = Provinsi::create(['nama_provinsi' => $provinsiName]);

            foreach ($kotaArray as $kotaName => $kecamatanArray) {
                // Create Kota
                $kota = Kota::create([
                    'provinsi_id' => $provinsi->id,
                    'nama_kota' => $kotaName,
                    'provinsi' => $provinsiName
                ]);

                foreach ($kecamatanArray as $kecamatanName => $kelurahanArray) {
                    // Create Kecamatan
                    $kecamatan = Kecamatan::create([
                        'kota_id' => $kota->id,
                        'nama_kecamatan' => $kecamatanName
                    ]);

                    // Create Kelurahan
                    foreach ($kelurahanArray as $kelurahanName) {
                        Kelurahan::create([
                            'kecamatan_id' => $kecamatan->id,
                            'nama_kelurahan' => $kelurahanName
                        ]);
                    }
                }
            }
        }
    }
}
