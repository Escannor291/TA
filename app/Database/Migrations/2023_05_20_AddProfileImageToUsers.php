<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileImageToUsers extends Migration
{
    public function up()
    {
        // Tambahkan kolom profile_image ke tabel users
        $this->forge->addColumn('users', [
            'profile_image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'role'
            ],
        ]);
    }

    public function down()
    {
        // Hapus kolom jika rollback
        $this->forge->dropColumn('users', 'profile_image');
    }
}
