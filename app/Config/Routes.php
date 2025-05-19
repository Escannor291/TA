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
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    
    // Rute Kelola Buku
    $routes->get('buku', 'Admin\Buku::index');
    $routes->get('buku/new', 'Admin\Buku::new');
    $routes->post('buku/create', 'Admin\Buku::create');
    $routes->get('buku/edit/(:num)', 'Admin\Buku::edit/$1');
    $routes->put('buku/update/(:num)', 'Admin\Buku::update/$1');
    $routes->delete('buku/delete/(:num)', 'Admin\Buku::delete/$1');
    
    // Rute Peminjaman
    $routes->get('peminjaman', 'Admin\Peminjaman::index');
    $routes->get('peminjaman/new', 'Admin\Peminjaman::new');
    $routes->post('peminjaman/create', 'Admin\Peminjaman::create');
    $routes->get('peminjaman/detail/(:num)', 'Admin\Peminjaman::detail/$1'); // Tambahkan route ini
    $routes->post('peminjaman/return/(:num)', 'Admin\Peminjaman::return/$1');
    $routes->post('peminjaman/delete/(:num)', 'Admin\Peminjaman::delete/$1');
    
    // Rute Pengembalian
    $routes->get('pengembalian', 'Admin\Pengembalian::index');
    $routes->get('pengembalian/detail/(:num)', 'Admin\Pengembalian::detail/$1');
    $routes->post('pengembalian/process/(:num)', 'Admin\Pengembalian::process/$1');
    $routes->get('pengembalian/report', 'Admin\Pengembalian::report');
    
    // Profile routes
    $routes->get('profile', 'Admin\Profile::index');
    $routes->post('profile/update', 'Admin\Profile::update');
    
    // Rute Kelola Anggota
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/new', 'Admin\Users::new');
    $routes->post('users/create', 'Admin\Users::create');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->put('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->delete('users/delete/(:num)', 'Admin\Users::delete/$1');
    
    // Setup Assets (tool untuk persiapan direktori)
    $routes->get('setup-assets', 'Admin\SetupAssets::index');
    
    // Setup Database
    $routes->get('setup-database', 'Admin\SetupDatabase::index');
    
    // Search route
    $routes->get('search', 'Admin\Search::index');
});

// User routes - pastikan hanya ada satu group untuk user
$routes->group('user', ['filter' => 'auth:anggota'], function($routes) {
    $routes->get('dashboard', 'User\Dashboard::index');
    
    // Tambahkan rute lainnya sesuai kebutuhan
    $routes->get('katalog', 'User\Katalog::index');
    $routes->get('peminjaman', 'User\Peminjaman::index');
    $routes->get('peminjaman/detail/(:num)', 'User\Peminjaman::detail/$1');
    $routes->get('profile', 'User\Profile::index');
    $routes->post('profile/update', 'User\Profile::update');
});