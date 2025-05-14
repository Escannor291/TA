<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run()
    {
       $data = [
            [
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2005,
                'isbn' => '9789793062792',
                'jumlah' => 5,
            ],
            [
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'penerbit' => 'Hasta Mitra',
                'tahun_terbit' => 1980,
                'isbn' => '9799731234',
                'jumlah' => 3,
            ],
            [
                'judul' => 'Tenggelamnya Kapal Van Der Wijck',
                'penulis' => 'Hamka',
                'penerbit' => 'Balai Pustaka',
                'tahun_terbit' => 1938,
                'isbn' => '9789794071410',
                'jumlah' => 2,
            ],
        ];

        // Tambahkan data ke tabel
        $this->db->table('buku')->insertBatch($data);  //
    }
}
