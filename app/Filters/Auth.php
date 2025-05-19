<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika pengguna belum login, redirect ke halaman login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/'));
        }
        
        // Jika argumen role diberikan, periksa role pengguna
        if ($arguments) {
            $userRole = session()->get('role');
            $allowed = false;
            
            // Periksa apakah role pengguna ada dalam daftar role yang diizinkan
            foreach ($arguments as $role) {
                if ($userRole == $role) {
                    $allowed = true;
                    break;
                }
            }
            
            if (!$allowed) {
                if ($userRole == 'admin') {
                    return redirect()->to(base_url('admin/dashboard'));
                } else if ($userRole == 'anggota') {
                    return redirect()->to(base_url('user/dashboard'));
                } else {
                    // Role lain jika ada
                    return redirect()->to(base_url('/'));
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
