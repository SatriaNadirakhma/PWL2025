<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            
            // Penjualan 1 (Sophie)
            [
                'penjualan_id' => 1,
                'barang_id' => 1, // Kapal Api Arabika 500ml
                'harga' => 15000,
                'jumlah' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'penjualan_id' => 1,
                'barang_id' => 2, // Sari Roti Dorayaki 200gr
                'harga' => 6000,
                'jumlah' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Penjualan 2 (Thomas)
            [
                'penjualan_id' => 2,
                'barang_id' => 3, // Kahf Facial Wash 100ml
                'harga' => 35000,
                'jumlah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Penjualan 3 (Emma)
            [
                'penjualan_id' => 3,
                'barang_id' => 4, // Garnier Micellar Water 150ml
                'harga' => 25000,
                'jumlah' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'penjualan_id' => 3,
                'barang_id' => 5, // Super Pel Apel 1l
                'harga' => 30000,
                'jumlah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Penjualan 4 (Lucas)
            [
                'penjualan_id' => 4,
                'barang_id' => 6, // Sunlight Jeruk Nipis 400ml
                'harga' => 22000,
                'jumlah' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Penjualan 5 (Olivia)
            [
                'penjualan_id' => 5,
                'barang_id' => 7, // Johnsons Popok Bayi 20pcs
                'harga' => 65000,
                'jumlah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'penjualan_id' => 5,
                'barang_id' => 8, // My Baby 100ml
                'harga' => 18000,
                'jumlah' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Penjualan 6 (Matteo)
            [
                'penjualan_id' => 6,
                'barang_id' => 1, // Kapal Api Arabika 500ml
                'harga' => 15000,
                'jumlah' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Penjualan 7 (Anna)
            [
                'penjualan_id' => 7,
                'barang_id' => 2, // Sari Roti Dorayaki 200gr
                'harga' => 6000,
                'jumlah' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'penjualan_id' => 7,
                'barang_id' => 3, // Kahf Facial Wash 100ml
                'harga' => 35000,
                'jumlah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Penjualan 8 (Noah)
            [
                'penjualan_id' => 8,
                'barang_id' => 4, // Garnier Micellar Water 150ml
                'harga' => 25000,
                'jumlah' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Penjualan 9 (Julia)
            [
                'penjualan_id' => 9,
                'barang_id' => 5, // Super Pel Apel 1l
                'harga' => 30000,
                'jumlah' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'penjualan_id' => 9,
                'barang_id' => 6, // Sunlight Jeruk Nipis 400ml
                'harga' => 22000,
                'jumlah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Penjualan 10 (Elias)
            [
                'penjualan_id' => 10,
                'barang_id' => 7, // Johnsons Popok Bayi 20pcs
                'harga' => 65000,
                'jumlah' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('t_penjualan_detail')->insert($data);
    }
}