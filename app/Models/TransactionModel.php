<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    // Nama tabel di database
    protected $table            = 'transaction';

    // Primary key dari tabel
    protected $primaryKey       = 'id';

    // Tipe data yang dikembalikan
    protected $returnType       = 'array';

    // Apakah menggunakan soft deletes
    protected $useSoftDeletes   = false;

    // --- PERBAIKAN DI SINI ---
    // Tambahkan 'invoice_id' ke dalam daftar kolom yang diizinkan.
    protected $allowedFields    = [
        'invoice_id', // <-- WAJIB DITAMBAHKAN
        'username',
        'total_harga',
        'total_diskon',
        'alamat',
        'ongkir',
        'status',
        'metode_pembayaran',
        'kode_pembayaran',
    ];

    // Menggunakan timestamp otomatis untuk created_at dan updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
