<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\BarangModel;
use App\Models\SupplierModel;
use App\Models\UserModel;

class StokModel extends Model
{
    use HasFactory;
    protected $table = 't_stok';
    protected $primaryKey = 'stok_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah'];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(SupplierModel::class, 'supplier_id', 'supplier_id');
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
}
