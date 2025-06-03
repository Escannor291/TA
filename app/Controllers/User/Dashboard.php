<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\BukuModel;

class Dashboard extends BaseController
{
    protected $peminjamanModel;
    protected $bukuModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->bukuModel = new BukuModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        
        // Jika session user_id kosong, coba ambil dari database berdasarkan nama yang ada di session
        if (!$userId && session()->get('name')) {
            $userName = session()->get('name');
            
            $userModel = new \App\Models\UserModel();
            $user = $userModel->where('name', $userName)->first();
            
            if ($user) {
                $userId = $user['id'];
                // Set ulang session dengan data lengkap
                session()->set([
                    'user_id' => $user['id'],
                    'name' => $user['name'],
                    'role' => $user['role'] ?? 'anggota',
                    'username' => $user['username'] ?? $user['name']
                ]);
            }
        }
        
        // Jika masih tidak ada user_id, redirect ke login
        if (!$userId) {
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu');
        }
        
        // Ambil data peminjaman
        $peminjaman = [];
        try {
            $peminjaman = $this->peminjamanModel
                ->select('peminjaman.*, buku.judul, buku.penulis, buku.penerbit')
                ->join('buku', 'buku.id = peminjaman.buku_id', 'left')
                ->where('peminjaman.user_id', $userId)
                ->orderBy('peminjaman.tanggal_pinjam', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            $peminjaman = [];
        }

        // Hitung statistik peminjaman user
        $totalPeminjaman = count($peminjaman);
        $sedangDipinjam = 0;
        
        foreach ($peminjaman as $p) {
            if (isset($p['status']) && strtolower($p['status']) == 'dipinjam') {
                $sedangDipinjam++;
            }
        }

        $data = [
            'title' => 'Dashboard Anggota',
            'peminjaman' => $peminjaman,
            'totalPeminjaman' => $totalPeminjaman,
            'sedangDipinjam' => $sedangDipinjam
        ];

        return view('user/dashboard', $data);
    }
}
