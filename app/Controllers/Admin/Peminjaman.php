<?php

namespace App\Controllers\Admin;

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
        $this->peminjamanModel = new \App\Models\PeminjamanModel();
        $this->bukuModel = new \App\Models\BukuModel();
        $this->userModel = new \App\Models\UserModel();
    }
    
    public function index()
    {
        // Perbaiki query untuk join dengan tabel users tanpa kolom email yang tidak ada
        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, buku.judul, buku.penulis, buku.penerbit, users.name as nama_peminjam')
            ->join('buku', 'buku.id = peminjaman.buku_id', 'left')
            ->join('users', 'users.id = peminjaman.user_id', 'left')
            ->orderBy('peminjaman.tanggal_pinjam', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Data Peminjaman',
            'peminjaman' => $peminjaman
        ];

        return view('admin/peminjaman/index', $data);
    }
    
    public function new()
    {
        $data = [
            'title' => 'Tambah Peminjaman Baru',
            'validation' => \Config\Services::validation(),
            'buku' => $this->bukuModel->where('jumlah >', 0)->findAll(),
            'anggota' => $this->userModel->where('role', 'anggota')->findAll()
        ];
        
        return view('admin/peminjaman/form', $data);
    }
    
    public function create()
    {
        // Validasi input
        $rules = [
            'user_id' => 'required|numeric',
            'buku_id' => 'required|numeric',
            'tanggal_pinjam' => 'required|valid_date',
            'tanggal_kembali' => 'required|valid_date'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $bukuId = $this->request->getPost('buku_id');
        $buku = $this->bukuModel->find($bukuId);
        
        // Cek ketersediaan buku
        if ($buku['jumlah'] <= 0) {
            session()->setFlashdata('error', 'Buku tidak tersedia untuk dipinjam');
            return redirect()->back()->withInput();
        }
        
        // Cek apakah anggota sudah meminjam buku yang sama dan belum dikembalikan
        $existingLoan = $this->peminjamanModel
            ->where('user_id', $this->request->getPost('user_id'))
            ->where('buku_id', $bukuId)
            ->where('status', 'dipinjam')
            ->first();
        
        if ($existingLoan) {
            session()->setFlashdata('error', 'Anggota sudah meminjam buku ini dan belum dikembalikan');
            return redirect()->back()->withInput();
        }
        
        // Simpan data peminjaman
        $this->peminjamanModel->save([
            'user_id' => $this->request->getPost('user_id'),
            'buku_id' => $bukuId,
            'tanggal_pinjam' => $this->request->getPost('tanggal_pinjam'),
            'tanggal_kembali' => $this->request->getPost('tanggal_kembali'),
            'status' => 'dipinjam'
        ]);
        
        // Kurangi stok buku
        $this->bukuModel->update($bukuId, [
            'jumlah' => $buku['jumlah'] - 1
        ]);
        
        session()->setFlashdata('message', 'Peminjaman buku berhasil ditambahkan');
        return redirect()->to(base_url('admin/peminjaman'));
    }
    
    public function return($id = null)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        
        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan');
            return redirect()->to(base_url('admin/peminjaman'));
        }
        
        // Hitung denda jika terlambat
        $denda = 0;
        $tanggal_dikembalikan = date('Y-m-d'); // tanggal hari ini
        $tanggal_kembali = $peminjaman['tanggal_kembali'];
        
        // Jika pengembalian melebihi tanggal kembali (terlambat)
        if (strtotime($tanggal_dikembalikan) > strtotime($tanggal_kembali)) {
            $selisih = strtotime($tanggal_dikembalikan) - strtotime($tanggal_kembali);
            $selisih_hari = floor($selisih / (60 * 60 * 24));
            $denda = $selisih_hari * 1000; // Rp 1.000 per hari
        }
        
        // Update data peminjaman
        $this->peminjamanModel->update($id, [
            'tanggal_dikembalikan' => $tanggal_dikembalikan,
            'status' => 'dikembalikan',
            'denda' => $denda
        ]);
        
        // Update stok buku
        $buku = $this->bukuModel->find($peminjaman['buku_id']);
        if ($buku) {
            $this->bukuModel->update($peminjaman['buku_id'], [
                'jumlah' => $buku['jumlah'] + 1
            ]);
        }
        
        $message = 'Buku berhasil dikembalikan';
        if ($denda > 0) {
            $message .= ' dengan denda keterlambatan Rp ' . number_format($denda, 0, ',', '.');
        }
        
        session()->setFlashdata('message', $message);
        return redirect()->to(base_url('admin/peminjaman'));
    }
    
    public function delete($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        
        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan');
            return redirect()->to(base_url('admin/peminjaman'));
        }
        
        // Jika status masih dipinjam, kembalikan stok buku
        if ($peminjaman['status'] == 'dipinjam') {
            $buku = $this->bukuModel->find($peminjaman['buku_id']);
            $this->bukuModel->update($peminjaman['buku_id'], [
                'jumlah' => $buku['jumlah'] + 1
            ]);
        }
        
        // Hapus data peminjaman
        $this->peminjamanModel->delete($id);
        
        session()->setFlashdata('message', 'Data peminjaman berhasil dihapus');
        return redirect()->to(base_url('admin/peminjaman'));
    }
    
    public function detail($id = null)
    {
        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, users.name as nama_peminjam, users.username, buku.judul as judul_buku, buku.penulis, buku.penerbit, buku.isbn, buku.tahun_terbit')
            ->join('users', 'users.id = peminjaman.user_id')
            ->join('buku', 'buku.id = peminjaman.buku_id')
            ->where('peminjaman.id', $id)
            ->first();
            
        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan');
            return redirect()->to(base_url('admin/peminjaman'));
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
            'title' => 'Detail Peminjaman - Perpustakaan Fachri',
            'peminjaman' => $peminjaman,
            'terlambat' => $terlambat,
            'denda' => $denda,
            'status_keterlambatan' => $status_keterlambatan
        ];
        
        return view('admin/peminjaman/detail', $data);
    }
}
