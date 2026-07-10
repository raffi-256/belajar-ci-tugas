<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDiscountTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'tanggal' => [
                'type' => 'DATE',
            ],

            'nominal' => [
                'type' => 'DOUBLE',
            ],

            'created_at' => [
                'type' => 'DATETIME',
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Menjadikan id sebagai primary key
        $this->forge->addKey('id', true);

        // Mencegah tanggal diskon yang sama
        $this->forge->addUniqueKey('tanggal');

        // Membuat tabel discount
        $this->forge->createTable('discount');
    }

    public function down()
    {
        // Menghapus tabel ketika migration di-rollback
        $this->forge->dropTable('discount');
    }
}