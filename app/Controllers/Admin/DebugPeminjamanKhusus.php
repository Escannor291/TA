<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\UserModel;

class DebugPeminjamanKhusus extends BaseController
{
    public function index()
    {
        echo "<h1>Debug Peminjaman Khusus</h1>";
        echo "<style>body{font-family: Arial; margin: 20px;} .pass{color: green;} .fail{color: red;} .info{color: blue;} table{border-collapse: collapse; width: 100%;} th,td{border: 1px solid #ddd; padding: 8px;}</style>";
        
        $tests = [
            'Database Connection' => $this->testDatabaseConnection(),
            'Model Validation' => $this->testModelValidation(),
            'CSRF Token' => $this->testCSRFToken(),
            'Session Management' => $this->testSessionManagement(),
            'File Permissions' => $this->testFilePermissions(),
            'Form Processing' => $this->testFormProcessing()
        ];
        
        echo "<h2>Test Results:</h2>";
        echo "<table>";
        echo "<tr><th>Test</th><th>Status</th><th>Details</th></tr>";
        
        foreach ($tests as $testName => $result) {
            $status = $result['status'] ? '<span class="pass">✅ PASS</span>' : '<span class="fail">❌ FAIL</span>';
            echo "<tr>";
            echo "<td>$testName</td>";
            echo "<td>$status</td>";
            echo "<td>{$result['message']}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        echo "<br><a href='" . base_url('admin/dashboard') . "'>← Back to Dashboard</a>";
    }
    
    private function testDatabaseConnection()
    {
        try {
            $db = \Config\Database::connect();
            $db->query('SELECT 1');
            return ['status' => true, 'message' => 'Database connection OK'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
    
    private function testModelValidation()
    {
        try {
            $peminjamanModel = new PeminjamanModel();
            $testData = [
                'user_id' => 999,
                'buku_id' => 999,
                'tanggal_pinjam' => date('Y-m-d'),
                'tanggal_kembali' => date('Y-m-d', strtotime('+7 days')),
                'status' => 'dipinjam'
            ];
            
            $peminjamanModel->validate($testData);
            $errors = $peminjamanModel->errors();
            
            if (empty($errors)) {
                return ['status' => true, 'message' => 'Model validation OK'];
            } else {
                return ['status' => false, 'message' => 'Validation errors: ' . implode(', ', $errors)];
            }
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Model error: ' . $e->getMessage()];
        }
    }
    
    private function testCSRFToken()
    {
        try {
            $token = csrf_token();
            $hash = csrf_hash();
            
            if (!empty($token) && !empty($hash)) {
                return ['status' => true, 'message' => "CSRF Token: $token, Hash: $hash"];
            } else {
                return ['status' => false, 'message' => 'CSRF token generation failed'];
            }
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'CSRF error: ' . $e->getMessage()];
        }
    }
    
    private function testSessionManagement()
    {
        try {
            $sessionData = session()->get();
            $hasUserData = isset($sessionData['user_id']) && isset($sessionData['logged_in']);
            
            if ($hasUserData) {
                return ['status' => true, 'message' => "Session OK - User ID: {$sessionData['user_id']}"];
            } else {
                return ['status' => false, 'message' => 'Session data incomplete: ' . json_encode($sessionData)];
            }
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Session error: ' . $e->getMessage()];
        }
    }
    
    private function testFilePermissions()
    {
        try {
            $writableDir = WRITEPATH;
            $logsDir = WRITEPATH . 'logs';
            
            $writablePermissions = is_writable($writableDir);
            $logsPermissions = is_writable($logsDir);
            
            if ($writablePermissions && $logsPermissions) {
                return ['status' => true, 'message' => 'File permissions OK'];
            } else {
                return ['status' => false, 'message' => "Writable: $writablePermissions, Logs: $logsPermissions"];
            }
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'File permission error: ' . $e->getMessage()];
        }
    }
    
    private function testFormProcessing()
    {
        try {
            // Simulate form data
            $formData = [
                'csrf_test_name' => csrf_hash(),
                'user_id' => 3,
                'buku_id' => 1
            ];
            
            // Test validation rules
            $validation = \Config\Services::validation();
            $validation->setRules([
                'user_id' => 'required|is_natural_no_zero',
                'buku_id' => 'required|is_natural_no_zero'
            ]);
            
            if ($validation->run($formData)) {
                return ['status' => true, 'message' => 'Form processing validation OK'];
            } else {
                return ['status' => false, 'message' => 'Form validation errors: ' . implode(', ', $validation->getErrors())];
            }
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Form processing error: ' . $e->getMessage()];
        }
    }
}
