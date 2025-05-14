<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BukuModel;

class Buku extends BaseController
{
    protected $bukuModel;
    
    public function __construct()
    {
        $this->bukuModel = new BukuModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Kelola Buku',
            'buku' => $this->bukuModel->findAll()
        ];
        
        return view('admin/buku/index', $data);
    }
    
    public function new()
    {
        $data = [
            'title' => 'Tambah Buku Baru',
            'validation' => \Config\Services::validation()
        ];
        
        return view('admin/buku/form', $data);
    }
    
    public function create()
    {
        // Validasi input
        $rules = [
            'judul' => 'required|min_length[3]',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric|min_length[4]|max_length[4]',
            'isbn' => 'required|min_length[10]',
            'jumlah' => 'required|numeric|greater_than[0]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // Simpan data buku baru
        $this->bukuModel->save([
            'judul' => $this->request->getPost('judul'),
            'penulis' => $this->request->getPost('penulis'),
            'penerbit' => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'isbn' => $this->request->getPost('isbn'),
            'jumlah' => $this->request->getPost('jumlah')
        ]);
        
        session()->setFlashdata('message', 'Buku baru berhasil ditambahkan');
        return redirect()->to(base_url('admin/buku'));
    }
    
    public function edit($id = null)
    {
        $buku = $this->bukuModel->find($id);
        
        if (!$buku) {
            session()->setFlashdata('error', 'Buku tidak ditemukan');
            return redirect()->to(base_url('admin/buku'));
        }
        
        $data = [
            'title' => 'Edit Buku',
            'validation' => \Config\Services::validation(),
            'buku' => $buku
        ];
        
        return view('admin/buku/form', $data);
    }
    
    public function update($id = null)
    {
        // Validasi input
        $rules = [
            'judul' => 'required|min_length[3]',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric|min_length[4]|max_length[4]',
            'isbn' => 'required|min_length[10]',
            'jumlah' => 'required|numeric|greater_than[0]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // Update data buku
        $this->bukuModel->update($id, [
            'judul' => $this->request->getPost('judul'),
            'penulis' => $this->request->getPost('penulis'),
            'penerbit' => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'isbn' => $this->request->getPost('isbn'),
            'jumlah' => $this->request->getPost('jumlah')
        ]);
        
        session()->setFlashdata('message', 'Data buku berhasil diperbarui');
        return redirect()->to(base_url('admin/buku'));
    }
    
    public function delete($id = null)
    {
        $buku = $this->bukuModel->find($id);
        
        if (!$buku) {
            session()->setFlashdata('error', 'Buku tidak ditemukan');
            return redirect()->to(base_url('admin/buku'));
        }
        
        $this->bukuModel->delete($id);
        
        session()->setFlashdata('message', 'Buku berhasil dihapus');
        return redirect()->to(base_url('admin/buku'));
    }
}
