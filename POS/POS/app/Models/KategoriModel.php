<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\BarangModel;

class KategoriModel extends Model
{
    use HasFactory;

    protected $table = 'm_kategori';
    protected $primaryKey = 'kategori_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['kategori_nama', 'kategori_kode'];
    
    public function barang(): HasMany
    {
        return $this->hasMany(BarangModel::class, 'kategori_id', 'kategori_id');
    }
}
