<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\UserModel;

class DebugTool extends BaseController
{
    public function index()
    {
        echo "<h1>Debug Tool - Peminjaman Buku</h1>";
        echo "<style>body{font-family: Arial; margin: 20px;} table{border-collapse: collapse; width: 100%;} th,td{border: 1px solid #ddd; padding: 8px; text-align: left;} th{background-color: #f2f2f2;}</style>";
        
        // Check database connection
        try {
            $db = \Config\Database::connect();
            echo "<h2>✅ Database Connection: OK</h2>";
        } catch (\Exception $e) {
            echo "<h2>❌ Database Connection Error: " . $e->getMessage() . "</h2>";
            return;
        }
        
        // Check tables structure
        echo "<h2>Database Tables Structure</h2>";
        
        // Check peminjaman table
        try {
            $fields = $db->getFieldData('peminjaman');
            echo "<h3>Tabel Peminjaman:</h3>";
            echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            foreach ($fields as $field) {
                echo "<tr>";
                echo "<td>" . $field->name . "</td>";
                echo "<td>" . $field->type . "</td>";
                echo "<td>" . ($field->nullable ? 'YES' : 'NO') . "</td>";
                echo "<td>" . ($field->primary_key ? 'PRI' : '') . "</td>";
                echo "<td>" . $field->default . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } catch (\Exception $e) {
            echo "<p>❌ Error checking peminjaman table: " . $e->getMessage() . "</p>";
        }
        
        // Check current user session
        echo "<h2>Session Information</h2>";
        echo "<p>User ID: " . (session()->get('user_id') ?? 'Not set') . "</p>";
        echo "<p>Username: " . (session()->get('username') ?? 'Not set') . "</p>";
        echo "<p>Role: " . (session()->get('role') ?? 'Not set') . "</p>";
        echo "<p>Logged in: " . (session()->get('logged_in') ? 'Yes' : 'No') . "</p>";
        
        // Test peminjaman model
        echo "<h2>Model Test</h2>";
        try {
            $peminjamanModel = new PeminjamanModel();
            $testData = [
                'user_id' => 999,
                'buku_id' => 999,
                'tanggal_pinjam' => date('Y-m-d'),
                'tanggal_kembali' => date('Y-m-d', strtotime('+7 days')),
                'status' => 'dipinjam'
            ];
            
            // Test validation without saving
            $peminjamanModel->validate($testData);
            $errors = $peminjamanModel->errors();
            
            if (empty($errors)) {
                echo "<p>✅ PeminjamanModel validation: OK</p>";
            } else {
                echo "<p>❌ PeminjamanModel validation errors:</p>";
                echo "<ul>";
                foreach ($errors as $error) {
                    echo "<li>" . $error . "</li>";
                }
                echo "</ul>";
            }
        } catch (\Exception $e) {
            echo "<p>❌ Model test error: " . $e->getMessage() . "</p>";
        }
        
        // Check CSRF
        echo "<h2>CSRF Token</h2>";
        echo "<p>CSRF Token: " . csrf_token() . "</p>";
        echo "<p>CSRF Hash: " . csrf_hash() . "</p>";
        
        echo "<br><a href='" . base_url('admin/dashboard') . "'>← Back to Dashboard</a>";
    }
}
