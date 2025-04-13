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
            ['kategori_id' => 1, 'kategori_kode' => 'FNB', 'kategori_nama' => 'Makanan dan Minuman'],
            ['kategori_id' => 2, 'kategori_kode' => 'BTY', 'kategori_nama' => 'Kecantikan'],
            ['kategori_id' => 3, 'kategori_kode' => 'HMC', 'kategori_nama' => 'Perawatan Rumah'],
            ['kategori_id' => 4, 'kategori_kode' => 'BBK', 'kategori_nama' => 'Perawatan Bayi'],
        ];
        
        DB::table('m_kategori')->insert($data);
    }
}
