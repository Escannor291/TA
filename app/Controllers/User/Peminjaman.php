<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\BukuModel;

class Peminjaman extends BaseController
{
    protected $peminjamanModel;
    protected $bukuModel;
    
    public function __construct()
    {
        $this->peminjamanModel = new \App\Models\PeminjamanModel();
        $this->bukuModel = new \App\Models\BukuModel();
    }
    
    public function index()
    {
        $userId = session()->get('id');
        
        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, buku.judul, buku.penulis, buku.gambar')
            ->join('buku', 'buku.id = peminjaman.buku_id')
            ->where('peminjaman.user_id', $userId)
            ->orderBy('peminjaman.created_at', 'DESC')
            ->findAll();
        
        $data = [
            'title' => 'Peminjaman Saya - Perpustakaan Fachri',
            'peminjaman' => $peminjaman
        ];
        
        return view('user/peminjaman/index', $data);
    }
    
    public function detail($id)
    {
        $userId = session()->get('id');
        
        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, buku.judul, buku.penulis, buku.penerbit, buku.tahun_terbit, buku.isbn, buku.gambar')
            ->join('buku', 'buku.id = peminjaman.buku_id')
            ->where('peminjaman.id', $id)
            ->where('peminjaman.user_id', $userId) // Pastikan hanya milik user yang login
            ->first();
        
        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan');
            return redirect()->to(base_url('user/peminjaman'));
        }
        
        // Hitung keterlambatan jika masih dipinjam
        $terlambat = 0;
        $denda = 0;
        $status_keterlambatan = 'Tepat Waktu';
        
        if ($peminjaman['status'] == 'dipinjam') {
            $today = new \DateTime(date('Y-m-d'));
            $batas_kembali = new \DateTime($peminjaman['tanggal_kembali']);
            
            if ($today > $batas_kembali) {
                $selisih = $today->diff($batas_kembali);
                $terlambat = $selisih->days;
                $denda = $terlambat * 1000; // Denda Rp 1.000 per hari
                $status_keterlambatan = 'Terlambat ' . $terlambat . ' hari';
            }
        } else if ($peminjaman['status'] == 'dikembalikan') {
            $denda = $peminjaman['denda'] ?? 0;
            
            if (!empty($peminjaman['tanggal_dikembalikan'])) {
                $tgl_kembali = new \DateTime($peminjaman['tanggal_dikembalikan']);
                $batas_kembali = new \DateTime($peminjaman['tanggal_kembali']);
                
                if ($tgl_kembali > $batas_kembali) {
                    $selisih = $tgl_kembali->diff($batas_kembali);
                    $terlambat = $selisih->days;
                    $status_keterlambatan = 'Terlambat ' . $terlambat . ' hari';
                }
            }
        }
        
        $data = [
            'title' => 'Detail Peminjaman - Perpustakaan Fachri',
            'peminjaman' => $peminjaman,
            'terlambat' => $terlambat,
            'denda' => $denda,
            'status_keterlambatan' => $status_keterlambatan
        ];
        
        return view('user/peminjaman/detail', $data);
    }
}
