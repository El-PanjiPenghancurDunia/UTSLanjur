<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TambahMetodePembayaran extends Migration
{
    public function up()
    {
        $fields = [
            'metode_pembayaran' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'after' => 'status',
            ],
            'kode_pembayaran' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'after' => 'metode_pembayaran',
            ],
        ];
        $this->forge->addColumn('transaction', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', ['metode_pembayaran', 'kode_pembayaran']);
    }
}