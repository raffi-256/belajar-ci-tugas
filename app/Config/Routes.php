<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ======================================================
// HOME
// ======================================================

$routes->get(
    '/',
    'Home::index',
    ['filter' => 'auth']
);


// ======================================================
// LOGIN DAN LOGOUT
// ======================================================

$routes->get(
    'login',
    'AuthController::login'
);

$routes->post(
    'login',
    'AuthController::login'
);

$routes->get(
    'logout',
    'AuthController::logout'
);


// ======================================================
// PRODUK
// ======================================================

$routes->group(
    'produk',
    ['filter' => 'auth'],
    function ($routes) {
        // Menampilkan daftar produk
        $routes->get(
            '',
            'ProdukController::index'
        );

        // Menambahkan produk
        $routes->post(
            '',
            'ProdukController::create'
        );

        // Mengubah produk
        $routes->post(
            'edit/(:any)',
            'ProdukController::edit/$1'
        );

        // Menghapus produk
        $routes->get(
            'delete/(:any)',
            'ProdukController::delete/$1'
        );

        // Mengunduh data produk
        $routes->get(
            'download',
            'ProdukController::download'
        );
    }
);


// ======================================================
// KERANJANG
// ======================================================

$routes->group(
    'keranjang',
    ['filter' => 'auth'],
    function ($routes) {
        // Menampilkan keranjang
        $routes->get(
            '',
            'TransaksiController::index'
        );

        // Menambahkan produk ke keranjang
        $routes->post(
            '',
            'TransaksiController::cart_add'
        );

        // Memperbarui jumlah produk
        $routes->post(
            'edit',
            'TransaksiController::cart_edit'
        );

        // Menghapus satu produk
        $routes->get(
            'delete/(:any)',
            'TransaksiController::cart_delete/$1'
        );

        // Mengosongkan keranjang
        $routes->get(
            'clear',
            'TransaksiController::cart_clear'
        );
    }
);


// ======================================================
// CHECKOUT DAN TRANSAKSI
// ======================================================

$routes->get(
    'checkout',
    'TransaksiController::checkout',
    ['filter' => 'auth']
);

$routes->post(
    'buy',
    'TransaksiController::buy',
    ['filter' => 'auth']
);

$routes->get(
    'history',
    'TransaksiController::history',
    ['filter' => 'auth']
);


// ======================================================
// RAJAONGKIR AJAX
// ======================================================

$routes->get(
    'ajax/destinations',
    'TransaksiController::destinations',
    ['filter' => 'auth']
);

$routes->get(
    'ajax/costs',
    'TransaksiController::costs',
    ['filter' => 'auth']
);


// ======================================================
// CONTACT
// ======================================================

$routes->get(
    'contact',
    'ContactController::index',
    ['filter' => 'auth']
);


// ======================================================
// DISCOUNT
// ======================================================

$routes->group(
    'discount',
    ['filter' => 'auth'],
    function ($routes) {
        // Menampilkan daftar diskon
        $routes->get(
            '',
            'DiscountController::index'
        );

        // Menambahkan diskon
        $routes->post(
            'store',
            'DiscountController::store'
        );

        // Mengubah diskon
        $routes->post(
            'update/(:num)',
            'DiscountController::update/$1'
        );

        // Menghapus diskon
        $routes->post(
            'delete/(:num)',
            'DiscountController::delete/$1'
        );
    }
);


// ======================================================
// PEMBELIAN KHUSUS ADMIN
// ======================================================

$routes->group(
    'pembelian',
    ['filter' => 'auth'],
    function ($routes) {
        // Menampilkan seluruh transaksi pembelian
        $routes->get(
            '',
            'PembelianController::index'
        );

        // Menampilkan detail pembelian
        $routes->get(
            'detail/(:num)',
            'PembelianController::detail/$1'
        );

        // Mengubah status pembelian
        $routes->post(
            'status/(:num)',
            'PembelianController::updateStatus/$1'
        );
    }
);


// ======================================================
// API PRODUK
// ======================================================

$routes->resource(
    'api/products',
    [
        'controller' => 'Api\ProdukController',
    ]
);


// ======================================================
// API TRANSAKSI
// ======================================================

$routes->get(
    'api/transactions',
    'Api\TransaksiController::index'
);