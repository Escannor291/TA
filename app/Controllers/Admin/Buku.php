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
            'title' => 'Kelola Buku - Perpustakaan Fachri',
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
        
        // Validasi gambar jika ada
        $gambar = $this->request->getFile('gambar');
        if ($gambar && $gambar->isValid()) {
            $rules['gambar'] = 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // Siapkan data untuk menyimpan
        $data = [
            'judul' => $this->request->getPost('judul'),
            'penulis' => $this->request->getPost('penulis'),
            'penerbit' => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'isbn' => $this->request->getPost('isbn'),
            'jumlah' => $this->request->getPost('jumlah')
        ];
        
        // Proses upload gambar jika ada
        if ($gambar && $gambar->isValid() && !$gambar->hasMoved()) {
            // Buat direktori jika belum ada
            $uploadPath = './uploads/buku';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Generate nama unik untuk file gambar
            $fileName = $gambar->getRandomName();
            
            // Pindahkan file ke direktori uploads
            if ($gambar->move($uploadPath, $fileName)) {
                // Simpan path ke database
                $data['gambar'] = 'uploads/buku/' . $fileName;
                log_message('info', 'Gambar berhasil diupload: ' . $data['gambar']);
            } else {
                log_message('error', 'Gagal mengupload gambar: ' . $gambar->getErrorString());
                session()->setFlashdata('error', 'Gagal mengupload gambar buku');
                return redirect()->back()->withInput();
            }
        }
        
        // Simpan data ke database
        if ($this->bukuModel->insert($data)) {
            session()->setFlashdata('message', 'Buku baru berhasil ditambahkan');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan buku baru');
        }
        
        return redirect()->to(base_url('admin/buku'));
    }
    
    public function edit($id)
    {
        $buku = $this->bukuModel->find($id);
        
        if (!$buku) {
            session()->setFlashdata('error', 'Data buku tidak ditemukan');
            return redirect()->to(base_url('admin/buku'));
        }
        
        // Pastikan semua field ada, jika tidak beri nilai default
        if (!isset($buku['deskripsi'])) {
            $buku['deskripsi'] = '';
        }
        
        $data = [
            'title' => 'Edit Buku',
            'buku' => $buku,
            'validation' => \Config\Services::validation()
        ];
        
        return view('admin/buku/form', $data);
    }
    
    public function update($id)
    {
        $buku = $this->bukuModel->find($id);
        
        if (!$buku) {
            session()->setFlashdata('error', 'Data buku tidak ditemukan');
            return redirect()->to(base_url('admin/buku'));
        }
        
        // Validasi input
        $rules = [
            'judul' => 'required|min_length[3]',
            'penulis' => 'required|min_length[3]',
            'penerbit' => 'required|min_length[3]',
            'tahun_terbit' => 'required|numeric|min_length[4]',
            'jumlah' => 'required|numeric|greater_than[0]',
            'isbn' => 'required'
        ];
        
        // Validasi foto jika ada upload
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $rules['foto'] = 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // Data yang akan diupdate
        $data = [
            'judul' => $this->request->getPost('judul'),
            'penulis' => $this->request->getPost('penulis'),
            'penerbit' => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'jumlah' => $this->request->getPost('jumlah'),
            'isbn' => $this->request->getPost('isbn'),
            'deskripsi' => $this->request->getPost('deskripsi') ?? '' // Berikan default value
        ];
        
        // Handle upload foto baru
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            try {
                // Hapus foto lama jika ada
                if (!empty($buku['foto']) && file_exists(FCPATH . 'uploads/buku/' . $buku['foto'])) {
                    unlink(FCPATH . 'uploads/buku/' . $buku['foto']);
                }
                
                // Generate nama file unik
                $newName = $foto->getRandomName();
                
                // Pindahkan file ke folder uploads/buku
                if ($foto->move(FCPATH . 'uploads/buku/', $newName)) {
                    $data['foto'] = $newName;
                } else {
                    session()->setFlashdata('error', 'Gagal mengupload foto');
                    return redirect()->back()->withInput();
                }
                
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Terjadi kesalahan saat mengupload foto: ' . $e->getMessage());
                return redirect()->back()->withInput();
            }
        }
        
        try {
            // Update data buku
            $this->bukuModel->update($id, $data);
            
            session()->setFlashdata('message', 'Data buku berhasil diperbarui');
            return redirect()->to(base_url('admin/buku'));
            
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    
    public function delete($id = null)
    {
        $buku = $this->bukuModel->find($id);
        
        if (!$buku) {
            session()->setFlashdata('error', 'Buku tidak ditemukan');
            return redirect()->to(base_url('admin/buku'));
        }
        
        // Hapus gambar jika ada
        if (!empty($buku['gambar']) && file_exists(FCPATH . $buku['gambar'])) {
            unlink(FCPATH . $buku['gambar']);
        }
        
        $this->bukuModel->delete($id);
        
        session()->setFlashdata('message', 'Buku berhasil dihapus');
        return redirect()->to(base_url('admin/buku'));
    }
}
