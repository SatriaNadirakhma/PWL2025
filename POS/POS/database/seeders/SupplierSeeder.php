<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_id'    => 1,
                'supplier_kode'  => 'SUP001',
                'supplier_nama'  => 'PT. Sinar Jaya Abadi',
                'supplier_alamat'=> 'Jl. Gatot Subroto No. 12, Jakarta'
            ],
            [
                'supplier_id'    => 2,
                'supplier_kode'  => 'SUP002',
                'supplier_nama'  => 'CV. Cahaya Nusantara',
                'supplier_alamat'=> 'Jl. Sudirman No. 56, Bandung'
            ],
            [
                'supplier_id'    => 3,
                'supplier_kode'  => 'SUP003',
                'supplier_nama'  => 'UD. Makmur Sentosa',
                'supplier_alamat'=> 'Jl. Diponegoro No. 98, Surabaya'
            ],
            [
                'supplier_id'    => 4,
                'supplier_kode'  => 'SUP004',
                'supplier_nama'  => 'PT. Berkat Sejahtera',
                'supplier_alamat'=> 'Jl. Ahmad Yani No. 78, Semarang'
            ],
            [
                'supplier_id'    => 5,
                'supplier_kode'  => 'SUP005',
                'supplier_nama'  => 'CV. Mandiri Jaya',
                'supplier_alamat'=> 'Jl. Pemuda No. 33, Yogyakarta'
            ],
            [
                'supplier_id'    => 6,
                'supplier_kode'  => 'SUP006',
                'supplier_nama'  => 'UD. Sukses Makmur',
                'supplier_alamat'=> 'Jl. Kartini No. 21, Malang'
            ],
            [
                'supplier_id'    => 7,
                'supplier_kode'  => 'SUP007',
                'supplier_nama'  => 'PT. Cipta Karya Mandiri',
                'supplier_alamat'=> 'Jl. Merdeka No. 11, Medan'
            ],
            [
                'supplier_id'    => 8,
                'supplier_kode'  => 'SUP008',
                'supplier_nama'  => 'CV. Harmoni Bersama',
                'supplier_alamat'=> 'Jl. Jenderal Sudirman No. 25, Palembang'
            ],
            [
                'supplier_id'    => 9,
                'supplier_kode'  => 'SUP009',
                'supplier_nama'  => 'PT. Mitra Usaha Bersama',
                'supplier_alamat'=> 'Jl. Soekarno-Hatta No. 44, Makassar'
            ],
            [
                'supplier_id'    => 10,
                'supplier_kode'  => 'SUP010',
                'supplier_nama'  => 'UD. Bintang Terang',
                'supplier_alamat'=> 'Jl. Dipatiukur No. 77, Balikpapan'
            ]
        ];
        DB::table('m_supplier')->insert($data);
    }
}
