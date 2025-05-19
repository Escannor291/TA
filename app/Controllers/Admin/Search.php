<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\UserModel;
use App\Models\PeminjamanModel;

class Search extends BaseController
{
    public function index()
    {
        $query = $this->request->getGet('q');
        $results = [];
        
        if (!empty($query)) {
            // Cari di buku
            $bukuModel = new BukuModel();
            $bukuResults = $bukuModel->like('judul', $query)
                ->orLike('penulis', $query)
                ->orLike('isbn', $query)
                ->findAll(5); // Batasi 5 hasil
                
            foreach ($bukuResults as $buku) {
                $results[] = [
                    'id' => $buku['id'],
                    'type' => 'buku',
                    'title' => $buku['judul'],
                    'subtitle' => 'Penulis: ' . $buku['penulis']
                ];
            }
            
            // Cari di anggota
            $userModel = new UserModel();
            $userResults = $userModel->like('name', $query)
                ->orLike('username', $query)
                ->where('role', 'anggota')
                ->findAll(5);
                
            foreach ($userResults as $user) {
                $results[] = [
                    'id' => $user['id'],
                    'type' => 'anggota',
                    'title' => $user['name'],
                    'subtitle' => 'Username: ' . $user['username']
                ];
            }
            
            // Cari di peminjaman (berdasarkan nama peminjam atau judul buku)
            $peminjamanModel = new PeminjamanModel();
            $peminjamanResults = $peminjamanModel->select('peminjaman.id, buku.judul as judul_buku, users.name as nama_peminjam')
                ->join('buku', 'buku.id = peminjaman.buku_id')
                ->join('users', 'users.id = peminjaman.user_id')
                ->groupStart()
                    ->like('buku.judul', $query)
                    ->orLike('users.name', $query)
                ->groupEnd()
                ->findAll(5);
                
            foreach ($peminjamanResults as $peminjaman) {
                $results[] = [
                    'id' => $peminjaman['id'],
                    'type' => 'peminjaman',
                    'title' => $peminjaman['judul_buku'],
                    'subtitle' => 'Dipinjam oleh: ' . $peminjaman['nama_peminjam']
                ];
            }
        }
        
        return $this->response->setJSON($results);
    }
}
