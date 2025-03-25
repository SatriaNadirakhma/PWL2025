<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) { // loop untuk 10 transaksi
            // Pilih 3 item secara acak dari rentang 1 sampai 10 (sesuai barang_id yang valid)
            $itemDipilih = array_rand(range(1, 10), 3);
            foreach ($itemDipilih as $index) {
                $barang_id = $index + 1; // menyesuaikan index ke barang_id (karena index array dimulai dari 0)
                // Mengambil harga jual dari tabel m_barang sesuai barang_id
                $harga = DB::table('m_barang')->where('barang_id', $barang_id)->value('harga_jual');
                $data[] = [
                    'detail_id'     => count($data) + 1, // jika field detail_id tidak auto increment, gunakan ini
                    'penjualan_id'  => $i,               // transaksi penjualan ke-i
                    'barang_id'     => $barang_id,       // barang yang telah dipilih
                    'harga'         => $harga,           // harga jual barang
                    'jumlah'        => rand(1, 5),       // jumlah pembelian secara acak (1-5)
                ];
            }
        }
        DB::table('t_penjualan_detail')->insert($data);
    }
}
