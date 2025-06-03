<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\UserModel;
use App\Models\BukuModel;

class DebugPeminjaman extends BaseController
{
    public function index()
    {
        echo "<h1>Debug Peminjaman Data</h1>";
        echo "<style>body{font-family: Arial; margin: 20px;} table{border-collapse: collapse; width: 100%; margin: 10px 0;} th,td{border: 1px solid #ddd; padding: 8px; text-align: left;} th{background-color: #f2f2f2;} .success{color: green;} .error{color: red;} .highlight{background-color: yellow;}</style>";
        
        $peminjamanModel = new PeminjamanModel();
        $userModel = new UserModel();
        $bukuModel = new BukuModel();
        
        // 1. Cek semua data peminjaman
        echo "<h2>1. Semua Data Peminjaman</h2>";
        $allPeminjaman = $peminjamanModel->findAll();
        echo "<p>Total records: " . count($allPeminjaman) . "</p>";
        
        if (!empty($allPeminjaman)) {
            echo "<table><tr><th>ID</th><th>User ID</th><th>Buku ID</th><th>Tanggal Pinjam</th><th>Status</th><th>Created At</th></tr>";
            foreach ($allPeminjaman as $p) {
                echo "<tr>";
                echo "<td>" . $p['id'] . "</td>";
                echo "<td class='highlight'>" . $p['user_id'] . "</td>";
                echo "<td>" . $p['buku_id'] . "</td>";
                echo "<td>" . $p['tanggal_pinjam'] . "</td>";
                echo "<td>" . $p['status'] . "</td>";
                echo "<td>" . ($p['created_at'] ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='error'>❌ Tidak ada data peminjaman ditemukan!</p>";
        }
        
        // 2. Cek data users dengan nama 'ariiiii'
        echo "<h2>2. Cari User 'ariiiii'</h2>";
        $users = $userModel->like('name', 'arii', 'both')->orLike('username', 'arii', 'both')->findAll();
        echo "<p>Found " . count($users) . " users matching 'arii'</p>";
        
        if (!empty($users)) {
            echo "<table><tr><th>ID</th><th>Name</th><th>Username</th><th>Role</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td class='highlight'>" . $user['id'] . "</td>";
                echo "<td>" . $user['name'] . "</td>";
                echo "<td>" . $user['username'] . "</td>";
                echo "<td>" . $user['role'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // 3. Cek session data saat ini
        echo "<h2>3. Current Session Data</h2>";
        $sessionData = session()->get();
        echo "<div style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
        echo "<pre>" . json_encode($sessionData, JSON_PRETTY_PRINT) . "</pre>";
        echo "</div>";
        
        // 4. Test join query untuk setiap user
        echo "<h2>4. Test Join Query untuk Setiap User</h2>";
        foreach ($users as $user) {
            $userId = $user['id'];
            echo "<h3>👤 User: {$user['name']} (ID: {$userId})</h3>";
            
            $userPeminjaman = $peminjamanModel
                ->select('peminjaman.*, buku.judul, buku.penulis')
                ->join('buku', 'buku.id = peminjaman.buku_id')
                ->where('peminjaman.user_id', $userId)
                ->findAll();
                
            echo "<p>Results: " . count($userPeminjaman) . " peminjaman</p>";
            
            if (!empty($userPeminjaman)) {
                echo "<table><tr><th>ID</th><th>Judul Buku</th><th>Tanggal Pinjam</th><th>Status</th></tr>";
                foreach ($userPeminjaman as $up) {
                    echo "<tr>";
                    echo "<td>" . $up['id'] . "</td>";
                    echo "<td>" . $up['judul'] . "</td>";
                    echo "<td>" . $up['tanggal_pinjam'] . "</td>";
                    echo "<td>" . $up['status'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='error'>❌ Tidak ada peminjaman untuk user ini</p>";
            }
        }
        
        // 5. Test login sebagai user tertentu
        echo "<h2>5. Test Login sebagai User Tertentu</h2>";
        echo "<p>Klik link di bawah untuk test login sebagai user tertentu:</p>";
        foreach ($users as $user) {
            echo "<p>🔗 <a href='" . base_url('admin/debug-peminjaman/test-login/' . $user['id']) . "' target='_blank' style='color: blue; text-decoration: underline;'>";
            echo "Test login sebagai {$user['name']} (ID: {$user['id']})</a></p>";
        }
        
        echo "<br><hr><br>";
        echo "<p><a href='" . base_url('admin/dashboard') . "' style='color: blue; text-decoration: underline;'>← Back to Dashboard</a></p>";
    }
    
    public function testLogin($userId)
    {
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        
        if (!$user) {
            echo "<h1>❌ User tidak ditemukan</h1>";
            echo "<p><a href='" . base_url('admin/debug-peminjaman') . "'>← Back to Debug</a></p>";
            return;
        }
        
        // Set session untuk test
        session()->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'name' => $user['name'],
            'role' => $user['role'],
            'logged_in' => true
        ]);
        
        echo "<h1>✅ Test Login untuk {$user['name']}</h1>";
        echo "<p>Session berhasil diset dengan data:</p>";
        echo "<ul>";
        echo "<li>User ID: {$user['id']}</li>";
        echo "<li>Username: {$user['username']}</li>";
        echo "<li>Name: {$user['name']}</li>";
        echo "<li>Role: {$user['role']}</li>";
        echo "</ul>";
        
        echo "<br>";
        echo "<p><strong>Test Links:</strong></p>";
        echo "<p>🔗 <a href='" . base_url('user/peminjaman') . "' target='_blank' style='color: blue; text-decoration: underline;'>Test Halaman Peminjaman Saya</a></p>";
        echo "<p>🔗 <a href='" . base_url('user/dashboard') . "' target='_blank' style='color: blue; text-decoration: underline;'>Test Dashboard User</a></p>";
        echo "<p>🔗 <a href='" . base_url('user/katalog') . "' target='_blank' style='color: blue; text-decoration: underline;'>Test Katalog Buku</a></p>";
        
        echo "<br><hr><br>";
        echo "<p><a href='" . base_url('admin/debug-peminjaman') . "' style='color: blue; text-decoration: underline;'>← Back to Debug</a></p>";
    }
}
