<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoProfilToUser extends Migration
{
    public function up()
    {
        // Mendefinisikan kolom baru yang akan ditambahkan
        $fields = [
            'foto_profil' => [ // Nama kolomnya kita samakan dengan di header
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true, // Boleh kosong untuk user yang belum upload
                'after'      => 'role', // Posisi kolom setelah kolom 'role'
            ],
        ];

        // Menambahkan kolom ke tabel 'user'
        $this->forge->addColumn('user', $fields);
    }

    public function down()
    {
        // Perintah untuk menghapus kolom jika migration di-rollback
        $this->forge->dropColumn('user', 'foto_profil');
    }
}