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
        $this->bukuModel = new BukuModel();
        $this->peminjamanModel = new PeminjamanModel();
        $this->userModel = new UserModel();
    }
    public function index()
    {
       $data = [
            'title' => 'Dashboard Admin',
            'total_buku' => $this->bukuModel->countAll(),
            'total_peminjaman' => $this->peminjamanModel->where('status', 'dipinjam')->countAllResults(),
            'total_anggota' => $this->userModel->where('role', 'anggota')->countAllResults(),
            'peminjaman_terbaru' => $this->peminjamanModel->select('peminjaman.*, users.name as nama_peminjam, buku.judul as judul_buku')
                ->join('users', 'users.id = peminjaman.user_id')
                ->join('buku', 'buku.id = peminjaman.buku_id')
                ->orderBy('peminjaman.created_at', 'DESC')
                ->limit(5)
                ->find()
        ];

        return view('admin/dashboard', $data);  //
    }
}
