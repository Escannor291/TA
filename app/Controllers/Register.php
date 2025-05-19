<?php

namespace App\Controllers;

use App\Models\UserModel;

class Register extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    public function index()
    {
        return view('auth/register');
    }
    
    public function create()
    {
        // Validasi input
        $rules = [
            'name' => 'required|min_length[3]',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // Simpan data pengguna baru
        $this->userModel->save([
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'anggota' // default role untuk user baru
        ]);
        
        session()->setFlashdata('message', 'Registrasi berhasil, silahkan login');
        return redirect()->to(base_url('/'));
    }
}
