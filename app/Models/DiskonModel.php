<?php
// app/Models/DiskonModel.php
namespace App\Models;

use CodeIgniter\Model;

class DiskonModel extends Model
{
    protected $table = 'diskon';
    protected $primaryKey = 'id';
    protected $useTimestamps = true; // Otomatis mengisi created_at & updated_at
    protected $allowedFields = [
        'nama_diskon', 'jumlah_diskon', 'tanggal_mulai', 'tanggal_selesai', 'created_at', 'updated_at'
    ];
}