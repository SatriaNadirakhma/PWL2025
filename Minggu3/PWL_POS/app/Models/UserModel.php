<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'm_user'; //mendefinisikan nama tabel yang digunakan model ini
    protected $primaryKey = 'user_id'; //mendefinisikan primary key dari tabel yang digunakan
}
