<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable; // implementasi class Authenticable

class UserModel extends Authenticatable implements JWTSubject
{
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    
    use HasFactory;
    protected $table = 'm_user'; //mendefinisikan nama tabel yang digunakan model ini
    protected $primaryKey = 'user_id'; //mendefinisikan primary key dari tabel yang digunakan

    protected $fillable = ['level_id', 'username', 'nama', 'password', 'created_at', 'updated_at', 'profile_picture'];

    //password protected
    protected $hidden = ['password']; // tidak menampilkan password
    protected $casts = ['password' => 'hashed']; // casting password ke hashed

    public function level(): BelongsTo 
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');     
    }
    /**
    * Mendapatkan nama role
    */
    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }

    /**
    * Cek apakah user memiliki role tertentu
    */
    public function hasRole($role): bool
    {
        return $this->level->level_kode == $role;
    }

    /**
     * Mendapatkan Kode Role
     */
    public function getRole()
    {
        return $this->level->level_kode;
    }
}
