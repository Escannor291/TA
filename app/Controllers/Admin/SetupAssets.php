<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class SetupAssets extends BaseController
{
    public function index()
    {
        // Buat direktori uploads jika belum ada
        $uploadDir = ROOTPATH . 'public/uploads/buku';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
            echo "Direktori upload buku berhasil dibuat: {$uploadDir}<br>";
        } else {
            echo "Direktori upload buku sudah ada: {$uploadDir}<br>";
        }
        
        // Buat direktori assets/img jika belum ada
        $assetDir = ROOTPATH . 'public/assets/img';
        if (!is_dir($assetDir)) {
            mkdir($assetDir, 0777, true);
            echo "Direktori assets/img berhasil dibuat: {$assetDir}<br>";
        } else {
            echo "Direktori assets/img sudah ada: {$assetDir}<br>";
        }
        
        // Cek izin direktori
        echo "<h3>Izin direktori:</h3>";
        echo "Upload dir writable: " . (is_writable($uploadDir) ? "Ya" : "Tidak") . "<br>";
        echo "Asset dir writable: " . (is_writable($assetDir) ? "Ya" : "Tidak") . "<br>";
        
        echo "<h3>Langkah-langkah selanjutnya:</h3>";
        echo "1. Pastikan path gambar di database benar (tanpa awalan / di depan)<br>";
        echo "2. Pastikan file gambar ada di lokasi yang benar<br>";
        echo "3. Pastikan izin akses file gambar benar<br>";
        
        return;
    }
}
