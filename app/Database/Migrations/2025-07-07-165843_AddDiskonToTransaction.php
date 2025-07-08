<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiskonToTransaction extends Migration
{
    public function up()
    {
        $fields = [
            'total_diskon' => [
                'type'       => 'DOUBLE',
                'null'       => true, // Boleh null jika tidak ada diskon
                'default'    => 0,
                'after'      => 'total_harga', // Posisi setelah total_harga
            ],
        ];
        $this->forge->addColumn('transaction', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', 'total_diskon');
    }
}