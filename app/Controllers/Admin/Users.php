<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Kelola Anggota - Perpustakaan Fachri',
            'users' => $this->userModel->where('role !=', 'admin')->findAll()
        ];
        
        return view('admin/users/index', $data);
    }
    
    public function new()
    {
        $data = [
            'title' => 'Tambah Anggota Baru - Perpustakaan Fachri',
            'validation' => \Config\Services::validation()
        ];
        
        return view('admin/users/form', $data);
    }
    
    public function create()
    {
        // Validasi input
        $rules = [
            'name' => 'required|min_length[3]',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[anggota,petugas]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // Simpan data anggota baru
        $this->userModel->save([
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role')
        ]);
        
        session()->setFlashdata('message', 'Anggota baru berhasil ditambahkan');
        return redirect()->to(base_url('admin/users'));
    }
    
    public function edit($id = null)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            session()->setFlashdata('error', 'Anggota tidak ditemukan');
            return redirect()->to(base_url('admin/users'));
        }
        
        $data = [
            'title' => 'Edit Anggota - Perpustakaan Fachri',
            'validation' => \Config\Services::validation(),
            'user' => $user
        ];
        
        return view('admin/users/form', $data);
    }
    
    public function update($id = null)
    {
        // Validasi input
        $rules = [
            'name' => 'required|min_length[3]',
            'username' => 'required|min_length[3]|is_unique[users.username,id,' . $id . ']',
            'role' => 'required|in_list[anggota,petugas]'
        ];
        
        // Jika password diisi, validasi password juga
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // Siapkan data untuk update
        $data = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'role' => $this->request->getPost('role')
        ];
        
        // Jika password diisi, update password juga
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }
        
        // Update data anggota
        $this->userModel->update($id, $data);
        
        session()->setFlashdata('message', 'Data anggota berhasil diperbarui');
        return redirect()->to(base_url('admin/users'));
    }
    
    public function delete($id = null)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            session()->setFlashdata('error', 'Anggota tidak ditemukan');
            return redirect()->to(base_url('admin/users'));
        }
        
        // Admin tidak bisa dihapus
        if ($user['role'] === 'admin') {
            session()->setFlashdata('error', 'Admin tidak dapat dihapus');
            return redirect()->to(base_url('admin/users'));
        }
        
        $this->userModel->delete($id);
        
        session()->setFlashdata('message', 'Anggota berhasil dihapus');
        return redirect()->to(base_url('admin/users'));
    }
}
