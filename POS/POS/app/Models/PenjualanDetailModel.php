<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetailModel extends Model
{
    use HasFactory;

    protected $table = 't_penjualan_detail';
    protected $primaryKey = 'detail_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['barang_id', 'penjualan_id', 'harga', 'jumlah'];

    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }

    public function penjualan()
    {
        return $this->belongsTo(PenjualanModel::class, 'penjualan_id', 'penjualan_id');
    }
}
