<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDendaColumnToPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addColumn('peminjaman', [
            'denda' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 0,
                'after' => 'status'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('peminjaman', 'denda');
    }
}
