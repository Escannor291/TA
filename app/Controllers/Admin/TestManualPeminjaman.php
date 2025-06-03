<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\UserModel;

class TestManualPeminjaman extends BaseController
{
    public function index()
    {
        echo "<h1>Test Manual Peminjaman - Fixed</h1>";
        echo "<style>body{font-family: Arial; margin: 20px;} .success{color: green;} .error{color: red;} .info{color: blue;} table{border-collapse: collapse; width: 100%;} th,td{border: 1px solid #ddd; padding: 8px;}</style>";
        
        $bukuModel = new BukuModel();
        $peminjamanModel = new PeminjamanModel();
        $userModel = new UserModel();
        
        // 1. Analisis data yang ada
        echo "<h2>1. Analisis Data:</h2>";
        $allBuku = $bukuModel->findAll();
        $allPeminjaman = $peminjamanModel->findAll();
        
        echo "<p>Total buku: " . count($allBuku) . "</p>";
        echo "<p>Total peminjaman: " . count($allPeminjaman) . "</p>";
        
        // 2. Cari buku yang tersedia dan belum dipinjam oleh user
        $userId = 3; // User fachri
        
        echo "<h2>2. Cari Buku yang Bisa Dipinjam oleh User ID $userId:</h2>";
        
        $availableBooks = [];
        foreach ($allBuku as $buku) {
            if ($buku['jumlah'] > 0) {
                // Cek apakah user sudah meminjam buku ini
                $existingLoan = $peminjamanModel
                    ->where('user_id', $userId)
                    ->where('buku_id', $buku['id'])
                    ->where('status', 'dipinjam')
                    ->first();
                    
                if (!$existingLoan) {
                    $availableBooks[] = $buku;
                }
            }
        }
        
        if (empty($availableBooks)) {
            echo "<p class='error'>❌ Tidak ada buku yang bisa dipinjam oleh user ini</p>";
            
            // Tampilkan detail peminjaman user
            echo "<h3>Peminjaman aktif user:</h3>";
            $userLoans = $peminjamanModel
                ->select('peminjaman.*, buku.judul')
                ->join('buku', 'buku.id = peminjaman.buku_id')
                ->where('peminjaman.user_id', $userId)
                ->where('peminjaman.status', 'dipinjam')
                ->findAll();
                
            if (!empty($userLoans)) {
                echo "<table><tr><th>ID Peminjaman</th><th>Judul Buku</th><th>Tanggal Pinjam</th></tr>";
                foreach ($userLoans as $loan) {
                    echo "<tr>";
                    echo "<td>" . $loan['id'] . "</td>";
                    echo "<td>" . $loan['judul'] . "</td>";
                    echo "<td>" . $loan['tanggal_pinjam'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
            return;
        }
        
        echo "<p class='success'>✅ Ditemukan " . count($availableBooks) . " buku yang bisa dipinjam</p>";
        
        // Tampilkan buku yang tersedia
        echo "<table><tr><th>ID</th><th>Judul</th><th>Stok</th></tr>";
        foreach ($availableBooks as $book) {
            echo "<tr>";
            echo "<td>" . $book['id'] . "</td>";
            echo "<td>" . $book['judul'] . "</td>";
            echo "<td>" . $book['jumlah'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // 3. Test dengan buku pertama yang tersedia
        $testBook = $availableBooks[0];
        $bukuId = $testBook['id'];
        
        echo "<h2>3. Test Peminjaman dengan Buku ID $bukuId:</h2>";
        echo "<p class='info'>Judul: " . $testBook['judul'] . "</p>";
        echo "<p class='info'>Stok: " . $testBook['jumlah'] . "</p>";
        
        // Cek user
        $user = $userModel->find($userId);
        if (!$user) {
            echo "<p class='error'>❌ User tidak ditemukan</p>";
            return;
        }
        echo "<p class='success'>✅ User: " . $user['name'] . "</p>";
        
        // Test insert peminjaman
        $dataPeminjaman = [
            'user_id' => $userId,
            'buku_id' => $bukuId,
            'tanggal_pinjam' => date('Y-m-d'),
            'tanggal_kembali' => date('Y-m-d', strtotime('+7 days')),
            'status' => 'dipinjam'
        ];
        
        echo "<h3>Data yang akan diinsert:</h3>";
        echo "<pre>" . json_encode($dataPeminjaman, JSON_PRETTY_PRINT) . "</pre>";
        
        try {
            $insertResult = $peminjamanModel->insert($dataPeminjaman);
            
            if ($insertResult) {
                $insertId = $peminjamanModel->getInsertID();
                echo "<p class='success'>✅ Insert peminjaman berhasil dengan ID: $insertId</p>";
                
                // Test update stok
                $updateResult = $bukuModel->update($bukuId, ['jumlah' => $testBook['jumlah'] - 1]);
                if ($updateResult) {
                    echo "<p class='success'>✅ Update stok berhasil dari " . $testBook['jumlah'] . " menjadi " . ($testBook['jumlah'] - 1) . "</p>";
                } else {
                    echo "<p class='error'>❌ Update stok gagal: " . json_encode($bukuModel->errors()) . "</p>";
                }
                
                echo "<p class='success'>🎉 PEMINJAMAN BERHASIL!</p>";
                
                // Cleanup - rollback untuk test
                $peminjamanModel->delete($insertId);
                $bukuModel->update($bukuId, ['jumlah' => $testBook['jumlah']]);
                echo "<p class='info'>🧹 Test data telah dibersihkan (rollback)</p>";
                
            } else {
                echo "<p class='error'>❌ Insert gagal: " . json_encode($peminjamanModel->errors()) . "</p>";
            }
            
        } catch (\Exception $e) {
            echo "<p class='error'>❌ Exception: " . $e->getMessage() . "</p>";
        }
        
        // 4. Rekomendasi untuk fix di katalog
        echo "<h2>4. Solusi untuk Katalog User:</h2>";
        echo "<p class='success'>📌 Gunakan salah satu buku berikut untuk test di katalog:</p>";
        echo "<ul>";
        foreach ($availableBooks as $book) {
            echo "<li>ID: <strong>" . $book['id'] . "</strong> - " . $book['judul'] . " (Stok: " . $book['jumlah'] . ")</li>";
        }
        echo "</ul>";
        
        echo "<p class='info'>💡 Atau kembalikan buku yang sudah dipinjam terlebih dahulu melalui admin.</p>";
        
        echo "<br><a href='" . base_url('admin/dashboard') . "'>← Back to Dashboard</a>";
        echo "<br><a href='" . base_url('admin/debug-peminjaman/test-login/3') . "'>Test Login sebagai User Fachri</a>";
    }
}
