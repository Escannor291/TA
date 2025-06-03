<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\BukuModel;
use App\Models\UserModel;

class Peminjaman extends BaseController
{
    protected $peminjamanModel;
    protected $bukuModel;
    protected $userModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->bukuModel = new BukuModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            session()->setFlashdata('error', 'Sesi login tidak valid');
            return redirect()->to('/');
        }
        
        // Cek apakah user exist di database
        $user = $this->userModel->find($userId);
        if (!$user) {
            session()->setFlashdata('error', 'Data user tidak ditemukan');
            return redirect()->to('/');
        }
        
        // Ambil semua peminjaman user dengan join ke tabel buku
        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, buku.judul, buku.penulis, buku.penerbit, buku.gambar')
            ->join('buku', 'buku.id = peminjaman.buku_id')
            ->where('peminjaman.user_id', $userId)
            ->orderBy('peminjaman.created_at', 'DESC')
            ->findAll();

        // Hitung statistik
        $totalPeminjaman = count($peminjaman);
        $peminjamanAktif = 0;
        $peminjamanSelesai = 0;
        $dendaTotal = 0;

        foreach ($peminjaman as $p) {
            if ($p['status'] == 'dipinjam') {
                $peminjamanAktif++;
            } else {
                $peminjamanSelesai++;
            }
            
            if (isset($p['denda']) && $p['denda'] > 0) {
                $dendaTotal += $p['denda'];
            }
        }

        $data = [
            'title' => 'Peminjaman Saya - Perpustakaan Fachri',
            'peminjaman' => $peminjaman,
            'total_peminjaman' => $totalPeminjaman,
            'peminjaman_aktif' => $peminjamanAktif,
            'peminjaman_selesai' => $peminjamanSelesai,
            'denda_total' => $dendaTotal
        ];

        return view('user/peminjaman/index', $data);
    }

    public function detail($id)
    {
        $userId = session()->get('user_id');
        
        // Ambil detail peminjaman dengan join ke tabel buku dan user
        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, buku.judul, buku.penulis, buku.penerbit, buku.gambar, buku.isbn, users.name as nama_peminjam')
            ->join('buku', 'buku.id = peminjaman.buku_id')
            ->join('users', 'users.id = peminjaman.user_id')
            ->where('peminjaman.id', $id)
            ->where('peminjaman.user_id', $userId) // Pastikan hanya user yang bersangkutan yang bisa akses
            ->first();

        if (!$peminjaman) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data peminjaman tidak ditemukan');
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
            // Jika sudah dikembalikan, ambil denda dari database
            $denda = $peminjaman['denda'] ?? 0;
            
            // Tetap hitung keterlambatan untuk informasi
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
            'title' => 'Detail Peminjaman - ' . $peminjaman['judul'],
            'peminjaman' => $peminjaman,
            'terlambat' => $terlambat,
            'denda' => $denda,
            'status_keterlambatan' => $status_keterlambatan
        ];

        return view('user/peminjaman/detail', $data);
    }
}
