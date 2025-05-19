<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\PeminjamanModel;

class Dashboard extends BaseController
{
    protected $bukuModel;
    protected $peminjamanModel;
    
    public function __construct()
    {
        $this->bukuModel = new \App\Models\BukuModel();
        $this->peminjamanModel = new \App\Models\PeminjamanModel();
    }
    
    public function index()
    {
        $userId = session()->get('id');
        
        $data = [
            'title' => 'Dashboard Anggota - Perpustakaan Fachri',
            'total_buku' => $this->bukuModel->countAll(),
            'total_pinjaman' => $this->peminjamanModel->where('user_id', $userId)->countAllResults(),
            'pinjaman_aktif' => $this->peminjamanModel
                ->where('user_id', $userId)
                ->where('status', 'dipinjam')
                ->countAllResults(),
            'riwayat_peminjaman' => $this->peminjamanModel
                ->select('peminjaman.*, buku.judul, buku.penulis, buku.gambar')
                ->join('buku', 'buku.id = peminjaman.buku_id')
                ->where('peminjaman.user_id', $userId)
                ->orderBy('peminjaman.created_at', 'DESC')
                ->limit(5)
                ->find()
        ];
        
        return view('user/dashboard', $data);
    }
}
