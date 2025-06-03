<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Debug logging
        log_message('info', 'Auth filter triggered');
        log_message('info', 'Session data: ' . json_encode(session()->get()));
        log_message('info', 'Arguments: ' . json_encode($arguments));
        
        // Jika pengguna belum login, redirect ke halaman login
        if (!session()->get('logged_in')) {
            log_message('info', 'User not logged in, redirecting to login');
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
                log_message('info', 'User role not allowed: ' . $userRole . ', required: ' . json_encode($arguments));
                if ($userRole == 'admin') {
                    return redirect()->to(base_url('admin/dashboard'));
                } else if ($userRole == 'anggota') {
                    return redirect()->to(base_url('user/dashboard'));
                } else {
                    return redirect()->to(base_url('/'));
                }
            }
        }
        
        log_message('info', 'Auth filter passed for user: ' . session()->get('username'));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
