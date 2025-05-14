<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Periksa apakah username admin sudah ada
        $user = $this->db->table('users')->where('username', 'admin')->get()->getRow();
        
        // Jika belum ada, tambahkan data admin
        if (!$user) {
            $data = [
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'name'     => 'Administrator',
                'role'     => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            
            // Tambahkan data ke tabel
            $this->db->table('users')->insert($data);
            
            echo "Akun admin berhasil dibuat.\n";
        } else {
            echo "Akun admin sudah ada, proses diabaikan.\n";
        }
    }
}
