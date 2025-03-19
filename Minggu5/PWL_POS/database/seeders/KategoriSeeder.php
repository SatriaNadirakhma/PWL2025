<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kategori_id' => 1, 'kategori_kode' => 'KSM', 'kategori_nama' => 'Kendaraan Sepeda Motor'],
            ['kategori_id' => 2, 'kategori_kode' => 'AMK', 'kategori_nama' => 'Alat Makan'],
            ['kategori_id' => 3, 'kategori_kode' => 'PKS', 'kategori_nama' => 'Perkakas'],
            ['kategori_id' => 4, 'kategori_kode' => 'ATK', 'kategori_nama' => 'Alat Tulis Kerja'],
            ['kategori_id' => 5, 'kategori_kode' => 'TPW', 'kategori_nama' => 'Tupperware'],
        ];
        
        DB::table('m_kategori')->insert($data);
    }
}
