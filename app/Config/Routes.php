<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * Router Setup
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * Route Definitions
 */

// Rute Publik & Autentikasi
$routes->get('/', 'AuthController::login');
$routes->match(['get', 'post'], 'login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// Rute Dashboard Utama (setelah login)
$routes->get('admin', 'DashboardController::admin', ['filter' => 'role:admin']);
$routes->get('home', 'DashboardController::user', ['filter' => 'role:user']);

// Rute Produk
$routes->group('produk', ['filter' => 'auth'], function ($routes) { 
    $routes->get('', 'ProdukController::index');
    $routes->post('', 'ProdukController::create');
    $routes->post('edit/(:any)', 'ProdukController::edit/$1');
    $routes->get('delete/(:any)', 'ProdukController::delete/$1');
    $routes->get('download', 'ProdukController::download');
});

// Rute Keranjang
$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    // --- PERBAIKAN DI SINI ---
    $routes->post('add', 'TransaksiController::cart_add');
    $routes->post('edit', 'TransaksiController::cart_edit');
    $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
    $routes->get('clear', 'TransaksiController::cart_clear');
});

// Rute Checkout & Proses Pesanan
$routes->get('checkout', 'TransaksiController::checkout', ['filter' => 'auth']);
$routes->post('buy', 'TransaksiController::buy', ['filter' => 'auth']);

// Rute untuk API Ongkir
$routes->get('get-location', 'TransaksiController::getLocation', ['filter' => 'auth']);
$routes->get('get-cost', 'TransaksiController::getCost', ['filter' => 'auth']);

// Rute Alur Pembayaran Lokal
$routes->group('payment', ['filter' => 'auth'], function ($routes) {
    $routes->get('choose/(:any)', 'TransaksiController::choosePayment/$1');
    $routes->post('generate', 'TransaksiController::generatePaymentCode');
    $routes->get('instruction/(:any)', 'TransaksiController::instruction/$1');
    $routes->post('confirm', 'TransaksiController::confirmPayment');
});

// Rute Riwayat Pemesanan
$routes->get('riwayat', 'TransaksiController::history', ['filter' => 'auth']);

// Rute Chatbot
$routes->post('ask-ai', 'ChatbotController::ask', ['filter' => 'auth']);

// Rute Khusus Admin
$routes->group('diskon', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('', 'DiskonController::index');
    $routes->post('create', 'DiskonController::create');
    $routes->post('edit/(:num)', 'DiskonController::edit/$1');
    $routes->get('delete/(:num)', 'DiskonController::delete/$1');
}); 

$routes->group('user', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('', 'UserController::index');
    $routes->post('create', 'UserController::create');
    $routes->post('edit/(:num)', 'UserController::edit/$1');
    $routes->get('delete/(:num)', 'UserController::delete/$1');
});

$routes->get('penjualan', 'PenjualanController::index', ['filter' => 'role:admin']);

// Rute Profil Pengguna
$routes->group('profile', ['filter' => 'auth'], function ($routes) {
    $routes->get('view', 'Profile::view');
    $routes->get('', 'Profile::index');
    $routes->post('change-password', 'Profile::changePassword');
    $routes->post('upload-photo', 'Profile::uploadPhoto');
});


/*
 * Additional Routing
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
