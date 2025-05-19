<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDendaColumn extends Migration
{
    public function up()
    {
        $this->forge->addColumn('peminjaman', [
            'denda' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 0,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('peminjaman', 'denda');
    }
}
