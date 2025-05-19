<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\BukuModel;

class Katalog extends BaseController
{
    protected $bukuModel;
    
    public function __construct()
    {
        $this->bukuModel = new \App\Models\BukuModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Katalog Buku - Perpustakaan Fachri',
            'buku' => $this->bukuModel->where('jumlah >', 0)->findAll()
        ];
        
        return view('user/katalog/index', $data);
    }
}
