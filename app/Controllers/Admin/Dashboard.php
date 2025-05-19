<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    protected $bukuModel;
    protected $peminjamanModel;
    protected $userModel;

    public function __construct()
    {
        $this->bukuModel = new \App\Models\BukuModel();
        $this->peminjamanModel = new \App\Models\PeminjamanModel();
        $this->userModel = new \App\Models\UserModel();
    }
    
    public function index()
    {
        // Kalkulasi data untuk dashboard
        $totalBukuTersedia = $this->bukuModel->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        $peminjamanHariIni = $this->peminjamanModel->where('DATE(created_at)', date('Y-m-d'))->countAllResults();
        
        // Hitung total denda yang terkumpul - dengan penanganan error
        $totalDenda = 0;
        try {
            // Cek dahulu apakah kolom denda ada
            $db = \Config\Database::connect();
            if ($db->fieldExists('denda', 'peminjaman')) {
                $totalDenda = $this->peminjamanModel->selectSum('denda')->get()->getRow()->denda ?? 0;
            }
        } catch (\Exception $e) {
            // Jika terjadi error, abaikan dan biarkan total denda = 0
            log_message('error', 'Error saat mengambil data denda: ' . $e->getMessage());
        }
        
        // Data untuk grafik peminjaman mingguan (7 hari terakhir)
        $chartData = $this->generateWeeklyChartData();
        
        // Peminjaman hampir jatuh tempo (3 hari lagi)
        $limitDate = date('Y-m-d', strtotime('+3 days'));
        $peminjamanHampirJatuhTempo = $this->peminjamanModel
            ->select('peminjaman.*, users.name as nama_peminjam, buku.judul as judul_buku')
            ->join('users', 'users.id = peminjaman.user_id')
            ->join('buku', 'buku.id = peminjaman.buku_id')
            ->where('peminjaman.status', 'dipinjam')
            ->where('peminjaman.tanggal_kembali <=', $limitDate)
            ->where('peminjaman.tanggal_kembali >=', date('Y-m-d'))
            ->orderBy('peminjaman.tanggal_kembali', 'ASC')
            ->findAll();
        
        // Buku paling sering dipinjam
        $bukuTerpopuler = $this->peminjamanModel
            ->select('buku.id, buku.judul, buku.penulis, buku.gambar, COUNT(peminjaman.id) as total_dipinjam')
            ->join('buku', 'buku.id = peminjaman.buku_id')
            ->groupBy('buku.id')
            ->orderBy('total_dipinjam', 'DESC')
            ->limit(5)
            ->find();
        
        $data = [
            'title' => 'Dashboard - Perpustakaan Fachri',
            'total_buku' => $this->bukuModel->countAll(),
            'total_peminjaman' => $this->peminjamanModel->where('status', 'dipinjam')->countAllResults(),
            'total_anggota' => $this->userModel->where('role', 'anggota')->countAllResults(),
            'total_denda' => $totalDenda,
            'peminjaman_terbaru' => $this->peminjamanModel->select('peminjaman.*, users.name as nama_peminjam, buku.judul as judul_buku')
                ->join('users', 'users.id = peminjaman.user_id')
                ->join('buku', 'buku.id = peminjaman.buku_id')
                ->orderBy('peminjaman.created_at', 'DESC')
                ->limit(5)
                ->find(),
            'total_buku_tersedia' => $totalBukuTersedia,
            'peminjaman_hari_ini' => $peminjamanHariIni,
            'chart_data' => $chartData,
            'peminjaman_hampir_jatuh_tempo' => $peminjamanHampirJatuhTempo,
            'buku_terpopuler' => $bukuTerpopuler
        ];

        return view('admin/dashboard', $data);
    }
    
    private function generateWeeklyChartData()
    {
        $chartData = [
            'labels' => [],
            'peminjaman' => [],
            'pengembalian' => []
        ];
        
        // Generate data untuk 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $formattedDate = date('d M', strtotime($date));
            
            // Hitung jumlah peminjaman per tanggal
            $totalPeminjaman = $this->peminjamanModel
                ->where('DATE(created_at)', $date)
                ->countAllResults();
            
            // Hitung jumlah pengembalian per tanggal
            $totalPengembalian = $this->peminjamanModel
                ->where('DATE(tanggal_dikembalikan)', $date)
                ->where('status', 'dikembalikan')
                ->countAllResults();
            
            $chartData['labels'][] = $formattedDate;
            $chartData['peminjaman'][] = $totalPeminjaman;
            $chartData['pengembalian'][] = $totalPengembalian;
        }
        
        return $chartData;
    }
}
