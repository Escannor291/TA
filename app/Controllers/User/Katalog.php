<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\PeminjamanModel;

class Katalog extends BaseController
{
    protected $bukuModel;
    protected $peminjamanModel;

    public function __construct()
    {
        $this->bukuModel = new BukuModel();
        $this->peminjamanModel = new PeminjamanModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Katalog Buku - Perpustakaan Fachri',
            'buku' => $this->bukuModel->findAll()
        ];

        return view('user/katalog/index', $data);
    }

    public function detail($id)
    {
        $buku = $this->bukuModel->find($id);
        
        if (!$buku) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Buku tidak ditemukan');
        }

        $userId = session()->get('user_id');
        $peminjamanAktif = $this->peminjamanModel
            ->where('user_id', $userId)
            ->where('buku_id', $id)
            ->where('status', 'dipinjam')
            ->first();

        $totalPeminjaman = $this->peminjamanModel
            ->where('buku_id', $id)
            ->countAllResults();

        $data = [
            'title' => 'Detail Buku - ' . $buku['judul'],
            'buku' => $buku,
            'peminjaman_aktif' => $peminjamanAktif,
            'total_peminjaman' => $totalPeminjaman,
            'dapat_dipinjam' => $buku['jumlah'] > 0 && !$peminjamanAktif
        ];

        return view('user/katalog/detail', $data);
    }

    public function pinjam($bukuId)
    {
        $userId = session()->get('user_id');
        
        // Jika tidak ada user_id di session, coba cari berdasarkan nama
        if (!$userId && session()->get('name')) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->where('name', session()->get('name'))->first();
            
            if ($user) {
                $userId = $user['id'];
                // Update session
                session()->set('user_id', $user['id']);
            }
        }
        
        // Jika masih tidak ada user_id, redirect ke login
        if (!$userId) {
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu');
        }
        
        // Validasi input
        if (!$bukuId || !is_numeric($bukuId)) {
            return redirect()->back()->with('error', 'ID buku tidak valid');
        }
        
        // Cek apakah buku masih tersedia
        $bukuModel = new \App\Models\BukuModel();
        $buku = $bukuModel->find($bukuId);
        
        if (!$buku) {
            return redirect()->back()->with('error', 'Buku tidak ditemukan');
        }
        
        // Cek stok (gunakan kolom yang sesuai dengan database Anda)
        $stokField = isset($buku['stok']) ? 'stok' : 'jumlah';
        if ($buku[$stokField] <= 0) {
            return redirect()->back()->with('error', 'Stok buku habis');
        }
        
        // Cek apakah user sudah meminjam buku yang sama dan belum dikembalikan
        $peminjamanModel = new \App\Models\PeminjamanModel();
        $existingPinjam = $peminjamanModel
            ->where('user_id', $userId)
            ->where('buku_id', $bukuId)
            ->where('status', 'dipinjam')
            ->first();
            
        if ($existingPinjam) {
            return redirect()->back()->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya');
        }
        
        // Hitung tanggal kembali (7 hari dari sekarang)
        $tanggalPinjam = date('Y-m-d');
        $tanggalKembali = date('Y-m-d', strtotime('+7 days'));
        
        // Insert data peminjaman
        $dataPinjam = [
            'user_id' => $userId,
            'buku_id' => $bukuId,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'status' => 'dipinjam',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        try {
            // Insert peminjaman
            $insertedId = $peminjamanModel->insert($dataPinjam);
            
            // Update stok buku (kurangi 1)
            $bukuModel->update($bukuId, [
                $stokField => $buku[$stokField] - 1
            ]);
            
            return redirect()->to('user/dashboard')->with('success', 'Buku berhasil dipinjam! Batas pengembalian: ' . date('d/m/Y', strtotime($tanggalKembali)));
            
        } catch (\Exception $e) {
            log_message('error', 'Error saat meminjam buku: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat meminjam buku: ' . $e->getMessage());
        }
    }
}
