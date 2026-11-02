<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', static fn () => redirect()->to('/dashboard'));

// Auth
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->post('logout', 'Auth::logout');

// Halaman yang butuh login
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');

    // Ganti password sendiri
    $routes->get('password', 'Profile::password');
    $routes->post('password', 'Profile::updatePassword');

    // Transaksi: semua role boleh lihat, tulis hanya admin & kasir
    $routes->get('transactions', 'Transactions::index');
    $routes->group('transactions', ['filter' => 'role:admin,kasir'], static function ($routes) {
        $routes->get('create', 'Transactions::create');
        $routes->post('/', 'Transactions::store');
        $routes->get('(:num)/edit', 'Transactions::edit/$1');
        $routes->post('(:num)/update', 'Transactions::update/$1');
        $routes->post('(:num)/delete', 'Transactions::delete/$1');
    });

    // Laporan
    $routes->get('reports', 'Reports::monthly');
    $routes->get('reports/csv', 'Reports::csv');

    // Master data & audit: admin saja
    $routes->group('', ['filter' => 'role:admin'], static function ($routes) {
        $routes->get('accounts', 'Accounts::index');
        $routes->get('accounts/create', 'Accounts::create');
        $routes->post('accounts', 'Accounts::store');
        $routes->get('accounts/(:num)/edit', 'Accounts::edit/$1');
        $routes->post('accounts/(:num)/update', 'Accounts::update/$1');
        $routes->post('accounts/(:num)/delete', 'Accounts::delete/$1');

        $routes->get('categories', 'Categories::index');
        $routes->post('categories', 'Categories::store');
        $routes->post('categories/(:num)/delete', 'Categories::delete/$1');

        $routes->get('audit', 'Audit::index');
    });
});

// REST API (JSON). Login via session; tulis hanya admin & kasir (dicek di controller).
$routes->group('api', ['filter' => 'auth'], static function ($routes) {
    $routes->resource('transactions', ['controller' => 'Api\TransactionApi', 'except' => 'new,edit']);
});
