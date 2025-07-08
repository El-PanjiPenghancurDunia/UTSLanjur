<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TambahBeratKeProduk extends Migration
{
    public function up()
    {
        // Mendefinisikan kolom baru yang akan ditambahkan
        $fields = [
            'berat' => [
                'type'       => 'INT',
                'constraint' => 11, // Angka 11 digit, cukup untuk berat dalam gram
                'unsigned'   => true, // Hanya angka positif
                'null'       => false, // Wajib diisi
                'default'    => 0, // Nilai default jika tidak diset
                'after'      => 'harga', // Posisi kolom setelah kolom 'harga'
            ],
        ];

        // Menambahkan kolom ke tabel 'product'
        $this->forge->addColumn('product', $fields);
    }

    public function down()
    {
        // Perintah untuk menghapus kolom jika migration di-rollback
        $this->forge->dropColumn('product', 'berat');
    }
}