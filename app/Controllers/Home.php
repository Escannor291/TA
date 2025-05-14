<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Mengalihkan halaman utama ke controller Auth
        return redirect()->to('auth');
    }
}
