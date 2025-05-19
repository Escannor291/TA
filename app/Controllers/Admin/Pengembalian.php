<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\BukuModel;
use App\Models\UserModel;

class Pengembalian extends BaseController
{
    protected $peminjamanModel;
    protected $bukuModel;
    protected $userModel;
    
    public function __construct()
    {
        $this->peminjamanModel = new \App\Models\PeminjamanModel();
        $this->bukuModel = new \App\Models\BukuModel();
        $this->userModel = new \App\Models\UserModel();
    }
    
    public function index()
    {
        // Filter data peminjaman berdasarkan status (default: hanya yang masih dipinjam)
        $status = $this->request->getGet('status') ?? 'dipinjam';
        
        // Ambil data peminjaman
        $builder = $this->peminjamanModel
            ->select('peminjaman.*, buku.judul as judul_buku, users.name as nama_peminjam')
            ->join('buku', 'buku.id = peminjaman.buku_id')
            ->join('users', 'users.id = peminjaman.user_id')
            ->orderBy('peminjaman.tanggal_pinjam', 'DESC');
            
        // Filter berdasarkan status
        if ($status != 'semua') {
            $builder->where('peminjaman.status', $status);
        }
        
        $peminjaman = $builder->findAll();
        
        $data = [
            'title' => 'Data Pengembalian - Perpustakaan Fachri',
            'peminjaman' => $peminjaman,
            'status' => $status
        ];
        
        return view('admin/pengembalian/index', $data);
    }
    
    public function process($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        
        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan');
            return redirect()->to(base_url('admin/pengembalian'));
        }
        
        if ($peminjaman['status'] == 'dikembalikan') {
            session()->setFlashdata('error', 'Buku sudah dikembalikan sebelumnya');
            return redirect()->to(base_url('admin/pengembalian'));
        }
        
        // Hitung denda jika terlambat
        $denda = 0;
        $tglKembali = new \DateTime($peminjaman['tanggal_kembali']);
        $tglHariIni = new \DateTime(date('Y-m-d'));
        
        if ($tglHariIni > $tglKembali) {
            $selisih = $tglHariIni->diff($tglKembali);
            $hari = $selisih->days;
            // Denda Rp 1.000 per hari keterlambatan
            $denda = $hari * 1000;
        }
        
        // Update status peminjaman
        $this->peminjamanModel->update($id, [
            'tanggal_dikembalikan' => date('Y-m-d'),
            'denda' => $denda,
            'status' => 'dikembalikan'
        ]);
        
        // Tambah stok buku
        $buku = $this->bukuModel->find($peminjaman['buku_id']);
        $this->bukuModel->update($peminjaman['buku_id'], [
            'jumlah' => $buku['jumlah'] + 1
        ]);
        
        session()->setFlashdata('message', 'Buku berhasil dikembalikan' . ($denda > 0 ? ' dengan denda Rp ' . number_format($denda, 0, ',', '.') : ''));
        return redirect()->to(base_url('admin/pengembalian'));
    }
    
    public function detail($id)
    {
        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, buku.judul as judul_buku, buku.penulis, buku.isbn, users.name as nama_peminjam, users.username')
            ->join('buku', 'buku.id = peminjaman.buku_id')
            ->join('users', 'users.id = peminjaman.user_id')
            ->where('peminjaman.id', $id)
            ->first();
        
        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan');
            return redirect()->to(base_url('admin/pengembalian'));
        }
        
        // Hitung keterlambatan
        $terlambat = 0;
        $status_keterlambatan = 'Tepat Waktu';
        
        if ($peminjaman['status'] == 'dikembalikan') {
            $tglKembali = new \DateTime($peminjaman['tanggal_kembali']);
            $tglDikembalikan = new \DateTime($peminjaman['tanggal_dikembalikan']);
            
            if ($tglDikembalikan > $tglKembali) {
                $selisih = $tglDikembalikan->diff($tglKembali);
                $terlambat = $selisih->days;
                $status_keterlambatan = 'Terlambat ' . $terlambat . ' hari';
            }
        } else {
            $tglKembali = new \DateTime($peminjaman['tanggal_kembali']);
            $tglHariIni = new \DateTime(date('Y-m-d'));
            
            if ($tglHariIni > $tglKembali) {
                $selisih = $tglHariIni->diff($tglKembali);
                $terlambat = $selisih->days;
                $status_keterlambatan = 'Terlambat ' . $terlambat . ' hari';
            }
        }
        
        $data = [
            'title' => 'Detail Pengembalian - Perpustakaan Fachri',
            'peminjaman' => $peminjaman,
            'terlambat' => $terlambat,
            'status_keterlambatan' => $status_keterlambatan
        ];
        
        return view('admin/pengembalian/detail', $data);
    }
    
    public function report()
    {
        // Ambil data untuk laporan
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        
        $builder = $this->peminjamanModel
            ->select('peminjaman.*, buku.judul as judul_buku, users.name as nama_peminjam')
            ->join('buku', 'buku.id = peminjaman.buku_id')
            ->join('users', 'users.id = peminjaman.user_id')
            ->where('MONTH(peminjaman.tanggal_dikembalikan)', $bulan)
            ->where('YEAR(peminjaman.tanggal_dikembalikan)', $tahun)
            ->where('peminjaman.status', 'dikembalikan')
            ->orderBy('peminjaman.tanggal_dikembalikan', 'DESC');
            
        $pengembalian = $builder->findAll();
        
        $data = [
            'title' => 'Laporan Pengembalian - Perpustakaan Fachri',
            'pengembalian' => $pengembalian,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'total_denda' => array_sum(array_column($pengembalian, 'denda'))
        ];
        
        return view('admin/pengembalian/report', $data);
    }
}
