<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Rute default ke Auth controller
$routes->get('/', 'Auth::index');

// Authentication routes
$routes->get('/', 'Auth::index');
$routes->get('auth', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');

// Registration routes
$routes->get('register', 'Register::index');
$routes->post('register/create', 'Register::create');

// Admin routes (dengan filter auth)
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    
    // Rute Kelola Buku
    $routes->get('buku', 'Admin\Buku::index');
    $routes->get('buku/new', 'Admin\Buku::new');
    $routes->post('buku/create', 'Admin\Buku::create');
    $routes->get('buku/edit/(:num)', 'Admin\Buku::edit/$1');
    $routes->post('buku/update/(:num)', 'Admin\Buku::update/$1');
    $routes->put('buku/update/(:num)', 'Admin\Buku::update/$1'); // Tambahkan route PUT
    $routes->patch('buku/update/(:num)', 'Admin\Buku::update/$1'); // Tambahkan route PATCH
    $routes->get('buku/delete/(:num)', 'Admin\Buku::delete/$1');
    
    // Rute Peminjaman
    $routes->get('peminjaman', 'Admin\Peminjaman::index');
    $routes->get('peminjaman/new', 'Admin\Peminjaman::new');
    $routes->post('peminjaman/create', 'Admin\Peminjaman::create');
    $routes->get('peminjaman/return/(:num)', 'Admin\Peminjaman::return/$1');
    $routes->get('peminjaman/delete/(:num)', 'Admin\Peminjaman::delete/$1');
    $routes->get('peminjaman/detail/(:num)', 'Admin\Peminjaman::detail/$1');
    
    // Rute Pengembalian
    $routes->get('pengembalian', 'Admin\Pengembalian::index');
    $routes->get('anggota', 'Admin\Anggota::index');
    $routes->get('anggota/new', 'Admin\Anggota::new');
    $routes->post('anggota/create', 'Admin\Anggota::create');
    $routes->get('anggota/edit/(:num)', 'Admin\Anggota::edit/$1');
    $routes->post('anggota/update/(:num)', 'Admin\Anggota::update/$1');
    $routes->get('anggota/delete/(:num)', 'Admin\Anggota::delete/$1');
});

// User routes - hapus semua filter untuk debugging
$routes->group('user', static function ($routes) {
    $routes->get('/', 'User\Dashboard::index');
    $routes->get('dashboard', 'User\Dashboard::index');
    $routes->get('peminjaman', 'User\Peminjaman::index');
    $routes->get('peminjaman/detail/(:num)', 'User\Peminjaman::detail/$1');
    $routes->get('katalog', 'User\Katalog::index');
    $routes->post('katalog/pinjam/(:num)', 'User\Katalog::pinjam/$1');
    $routes->get('profile', 'User\Profile::index');
    $routes->post('profile/update', 'User\Profile::update');
});