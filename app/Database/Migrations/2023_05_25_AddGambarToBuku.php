<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGambarToBuku extends Migration
{
    public function up()
    {
        $this->forge->addColumn('buku', [
            'gambar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('buku', 'gambar');
    }
}
