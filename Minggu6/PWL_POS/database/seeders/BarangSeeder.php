<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'OLI001', 'barang_nama' => 'Oli Motor 1L', 'harga_beli' => 45000, 'harga_jual' => 60000],
                ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'HLM001', 'barang_nama' => 'Helm SNI', 'harga_beli' => 150000, 'harga_jual' => 200000],
                ['barang_id' => 3, 'kategori_id' => 2, 'barang_kode' => 'SDK001', 'barang_nama' => 'Sendok Stainless', 'harga_beli' => 5000, 'harga_jual' => 10000],
                ['barang_id' => 4, 'kategori_id' => 2, 'barang_kode' => 'GRP001', 'barang_nama' => 'Gelas Plastik', 'harga_beli' => 3000, 'harga_jual' => 7000],
                ['barang_id' => 5, 'kategori_id' => 3, 'barang_kode' => 'SMP001', 'barang_nama' => 'Sapu Lidi', 'harga_beli' => 20000, 'harga_jual' => 30000],
                ['barang_id' => 6, 'kategori_id' => 3, 'barang_kode' => 'TMP001', 'barang_nama' => 'Tempat Sampah Besar', 'harga_beli' => 50000, 'harga_jual' => 70000],
                ['barang_id' => 7, 'kategori_id' => 4, 'barang_kode' => 'BLP001', 'barang_nama' => 'Bolpoin Biru', 'harga_beli' => 2000, 'harga_jual' => 5000],
                ['barang_id' => 8, 'kategori_id' => 4, 'barang_kode' => 'KRT001', 'barang_nama' => 'Kertas A4 80gsm', 'harga_beli' => 40000, 'harga_jual' => 55000],
                ['barang_id' => 9, 'kategori_id' => 5, 'barang_kode' => 'WDM001', 'barang_nama' => 'Wadah Makan Plastik', 'harga_beli' => 15000, 'harga_jual' => 25000],
                ['barang_id' => 10, 'kategori_id' => 5, 'barang_kode' => 'BOT001', 'barang_nama' => 'Botol Minum 1L', 'harga_beli' => 30000, 'harga_jual' => 45000],
        ];
        DB::table('m_barang')->insert($data);
    }
}
