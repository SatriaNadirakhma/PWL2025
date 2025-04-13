<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_id' => 1, // Makanan dan Minuman
                'barang_kode' => 'FNB001',
                'barang_nama' => 'Kapal Api Arabika 500ml',
                'harga_beli' => 10000,
                'harga_jual' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 1, // Makanan dan Minuman
                'barang_kode' => 'FNB002',
                'barang_nama' => 'Sari Roti Dorayaki 200gr',
                'harga_beli' => 4000,
                'harga_jual' => 6000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2, // Kecantikan
                'barang_kode' => 'BTY001',
                'barang_nama' => 'Kahf Facial Wash 100ml',
                'harga_beli' => 25000,
                'harga_jual' => 35000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2, // Kecantikan
                'barang_kode' => 'BTY002',
                'barang_nama' => 'Garnier Micellar Water 150ml',
                'harga_beli' => 19000,
                'harga_jual' => 25000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 3, // Perawatan Rumah
                'barang_kode' => 'HMC001',
                'barang_nama' => 'Super Pel Apel 1l',
                'harga_beli' => 20000,
                'harga_jual' => 30000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 3, // Perawatan Rumah
                'barang_kode' => 'HMC002',
                'barang_nama' => 'Sunlight Jeruk Nipis 400ml',
                'harga_beli' => 15000,
                'harga_jual' => 22000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 4, // Perawatan Bayi
                'barang_kode' => 'BBK001',
                'barang_nama' => 'Johnsons Popok Bayi 20pcs',
                'harga_beli' => 50000,
                'harga_jual' => 65000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 4, // Perawatan Bayi
                'barang_kode' => 'BBK002',
                'barang_nama' => 'My Baby 100ml',
                'harga_beli' => 13000,
                'harga_jual' => 18000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('m_barang')->insert($data);
    }
}