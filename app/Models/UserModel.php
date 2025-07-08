<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';

    // Menambahkan 'foto_profil' ke dalam daftar kolom yang diizinkan
    protected $allowedFields = [
        'username', 'email', 'password', 'role', 'foto_profil', 'created_at', 'updated_at'
    ];
}
