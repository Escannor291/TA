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
    
    $routes->get('peminjaman', 'Admin\Peminjaman::index');
    $routes->get('pengembalian', 'Admin\Pengembalian::index');
    $routes->get('users', 'Admin\Users::index');
});

// User routes
$routes->group('user', ['filter' => 'auth:user'], function($routes) {
    $routes->get('dashboard', 'User\Dashboard::index');
});