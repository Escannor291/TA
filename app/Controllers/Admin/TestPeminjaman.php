<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\UserModel;

class TestPeminjaman extends BaseController
{
    public function index()
    {
        echo "<h1>Test Peminjaman Manual</h1>";
        echo "<style>body{font-family: Arial; margin: 20px;} .success{color: green;} .error{color: red;}</style>";
        
        // Test data
        $userId = 1; // Ganti dengan user ID yang valid
        $bukuId = 1; // Ganti dengan buku ID yang valid
        
        try {
            $bukuModel = new BukuModel();
            $peminjamanModel = new PeminjamanModel();
            $userModel = new UserModel();
            
            // Cek user exists
            $user = $userModel->find($userId);
            if (!$user) {
                echo "<p class='error'>❌ User dengan ID $userId tidak ditemukan</p>";
                return;
            }
            echo "<p class='success'>✅ User ditemukan: " . $user['name'] . "</p>";
            
            // Cek buku exists
            $buku = $bukuModel->find($bukuId);
            if (!$buku) {
                echo "<p class='error'>❌ Buku dengan ID $bukuId tidak ditemukan</p>";
                return;
            }
            echo "<p class='success'>✅ Buku ditemukan: " . $buku['judul'] . " (Stok: " . $buku['jumlah'] . ")</p>";
            
            // Test insert peminjaman
            $dataPeminjaman = [
                'user_id' => $userId,
                'buku_id' => $bukuId,
                'tanggal_pinjam' => date('Y-m-d'),
                'tanggal_kembali' => date('Y-m-d', strtotime('+7 days')),
                'status' => 'dipinjam'
            ];
            
            echo "<h2>Test Insert Peminjaman</h2>";
            echo "<p>Data yang akan diinsert: " . json_encode($dataPeminjaman) . "</p>";
            
            if ($peminjamanModel->insert($dataPeminjaman)) {
                $insertId = $peminjamanModel->getInsertID();
                echo "<p class='success'>✅ Peminjaman berhasil diinsert dengan ID: $insertId</p>";
                
                // Test update stok buku
                if ($bukuModel->update($bukuId, ['jumlah' => $buku['jumlah'] - 1])) {
                    echo "<p class='success'>✅ Stok buku berhasil diupdate</p>";
                } else {
                    echo "<p class='error'>❌ Gagal update stok buku: " . json_encode($bukuModel->errors()) . "</p>";
                }
                
                // Cleanup - hapus data test
                $peminjamanModel->delete($insertId);
                $bukuModel->update($bukuId, ['jumlah' => $buku['jumlah']]);
                echo "<p>🧹 Data test telah dibersihkan</p>";
                
            } else {
                echo "<p class='error'>❌ Gagal insert peminjaman: " . json_encode($peminjamanModel->errors()) . "</p>";
            }
            
        } catch (\Exception $e) {
            echo "<p class='error'>❌ Exception: " . $e->getMessage() . "</p>";
        }
        
        echo "<br><a href='" . base_url('admin/dashboard') . "'>← Back to Dashboard</a>";
    }
}
