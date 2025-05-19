<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class SetupDatabase extends BaseController
{
    public function index()
    {
        echo "<h1>Setup Database</h1>";
        
        // Cek apakah kolom denda sudah ada di tabel peminjaman
        $db = \Config\Database::connect();
        if (!$db->fieldExists('denda', 'peminjaman')) {
            // Kolom belum ada, tambahkan kolom denda
            $forge = \Config\Database::forge();
            $forge->addColumn('peminjaman', [
                'denda' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,
                    'default' => 0,
                ],
            ]);
            
            echo "<p>Kolom denda berhasil ditambahkan ke tabel peminjaman!</p>";
        } else {
            echo "<p>Kolom denda sudah ada di tabel peminjaman.</p>";
        }
        
        echo "<p><a href='" . base_url('admin/dashboard') . "'>Kembali ke Dashboard</a></p>";
        return;
    }
}
