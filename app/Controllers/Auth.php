<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        helper(['form']);
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (session()->get('logged_in')) {
            if (session()->get('role') == 'admin') {
                return redirect()->to('admin/dashboard');
            } else {
                return redirect()->to('user/dashboard');
            }
        }

        // Tampilkan halaman login
        return view('auth/login', ['title' => 'Login']);
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $user = $this->userModel->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $session_data = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'profile_image' => $user['profile_image'] ?? null,
                    'logged_in' => TRUE
                ];
                session()->set($session_data);
                
                // Redirect berdasarkan role
                if ($user['role'] == 'admin') {
                    return redirect()->to('admin/dashboard');
                } else {
                    // Role anggota atau petugas diarahkan ke dashboard user
                    return redirect()->to('user/dashboard');
                }
            } else {
                session()->setFlashdata('error', 'Password yang Anda masukkan salah');
                return redirect()->back();
            }
        } else {
            session()->setFlashdata('error', 'Username tidak ditemukan');
            return redirect()->back();
        }
    }

    public function logout()
    {
        // Hapus semua data session
        session()->destroy();
        
        // Tambahkan pesan flashdata untuk konfirmasi logout
        session()->setFlashdata('message', 'Anda telah berhasil logout');
        
        // Redirect ke halaman login
        return redirect()->to(base_url('/'));
    }
}
