<?php
// app/Database/Migrations/YYYY-MM-DD-HHIISS_AddInvoiceIdToTransaction.php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInvoiceIdToTransaction extends Migration
{
    public function up()
    {
        $fields = [
            'invoice_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
                'null'       => false,
                'after'      => 'id', // Posisi kolom setelah kolom 'id'
            ],
        ];
        $this->forge->addColumn('transaction', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', 'invoice_id');
    }
}