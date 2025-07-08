<?php 
namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    // Nama tabel ini sudah benar sesuai database Anda
    protected $table = 'product'; 
    protected $primaryKey = 'id';
    
    // ======================================================= //
    //                PERBAIKAN UTAMA DI SINI                  //
    // ======================================================= //
    // Diubah dari 'satuan' kembali ke 'berat' agar sesuai dengan struktur database Anda.
    protected $allowedFields = [
        'nama', 'harga', 'jumlah', 'foto', 'berat', 'created_at', 'updated_at'
    ]; 
    
    // Ini sudah bagus untuk manajemen timestamp otomatis
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
