<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =============================================================================
//  GLOBAL OPTIONS
// =============================================================================
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override('App\Controllers\ErrorController::notFound');
$routes->setAutoRoute(false); // Wajib false — semua route harus eksplisit

// =============================================================================
//  1. AUTHENTICATION — Shield custom routes
//     Menimpa route default Shield agar menggunakan controller kita sendiri
//     sehingga view login bisa disesuaikan dengan Phoenix Admin theme.
// =============================================================================
$routes->get('login',  'Auth\LoginController::index',  ['as' => 'login']);
$routes->post('login', 'Auth\LoginController::attempt', ['as' => 'login.attempt']);
$routes->post('logout', 'Auth\LoginController::logout', ['as' => 'logout']);

// Register (nonaktifkan jika tidak ingin self-register)
// $routes->get('register', 'Auth\RegisterController::index');

// Forgot password
$routes->get('forgot-password', 'Auth\ForgotPasswordController::index', ['as' => 'forgot-password']);
$routes->get('reset-password/(:segment)', 'Auth\ForgotPasswordController::reset/$1', ['as' => 'reset-password']);
$routes->post('reset-password',           'Auth\ForgotPasswordController::process',  ['as' => 'reset-password.process']);

// =============================================================================
//  2. DASHBOARD UTAMA
// =============================================================================
// $routes->get('/',          'DashboardController::index', ['filter' => 'shield']);
$routes->get('/', function () {
    return redirect()->to('dashboard');
});
$routes->get('dashboard',  'DashboardController::index', ['as' => 'dashboard', 'filter' => 'shield']);

// Profile
$routes->group('profile', ['filter' => 'shield', 'namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/',          'ProfileController::index',          ['as' => 'profile']);
    $routes->post('update',    'ProfileController::update',         ['as' => 'profile.update']);
    $routes->get('password',   'ProfileController::password',       ['as' => 'profile.password']);
    $routes->post('password',  'ProfileController::updatePassword', ['as' => 'profile.password.update']);
});

// =============================================================================
//  3. MODUL: HRM
// =============================================================================
require APPPATH . 'Modules/HRM/Config/Routes.php';

// =============================================================================
//  4. MODUL: PRODUCTION
// =============================================================================
require APPPATH . 'Modules/Production/Config/Routes.php';

// =============================================================================
//  5. MODUL: WAREHOUSE
// =============================================================================
require APPPATH . 'Modules/Warehouse/Config/Routes.php';

// =============================================================================
//  6. MANAJEMEN HAK AKSES (User, Group, Permission via Shield)
// =============================================================================
$routes->group('access', ['filter' => 'shield', 'namespace' => 'Access'], function ($routes) {

    // Users
    $routes->get('users',                   'UserController::index',        ['as' => 'access.users']);
    $routes->get('users/datatables',        'UserController::datatables');
    $routes->get('users/(:num)',            'UserController::show/$1',      ['as' => 'access.users.show']);
    $routes->post('users/(:num)/store',     'UserController::store/$1');
    $routes->post('users/(:num)/toggle',    'UserController::toggle/$1');
    $routes->post('users/(:num)/delete',    'UserController::delete/$1');
    $routes->post('users/(:num)/groups',    'UserController::assignGroups/$1');

    // Groups (Role)
    $routes->get('groups',                  'GroupController::index',       ['as' => 'access.groups']);
    $routes->get('groups/datatables',       'GroupController::datatables');
    $routes->get('groups/(:num)',           'GroupController::getById/$1');
    $routes->post('groups/store',           'GroupController::store');
    $routes->post('groups/(:num)/delete',   'GroupController::delete/$1');
    $routes->post('groups/(:num)/permissions', 'GroupController::assignPermissions/$1');

    // Permissions
    $routes->get('permissions',             'PermissionController::index',  ['as' => 'access.permissions']);
    $routes->get('permissions/datatables',  'PermissionController::datatables');
    $routes->get('permissions/(:num)',      'PermissionController::getById/$1');
    $routes->post('permissions/store',      'PermissionController::store');
    $routes->post('permissions/(:num)/delete', 'PermissionController::delete/$1');
});

// =============================================================================
//  7. CUSTOM ERROR PAGES
//     Dipanggil otomatis oleh set404Override dan exception handler
// =============================================================================
$routes->get('errors/404',  'ErrorController::notFound',    ['as' => 'error.404']);
$routes->get('errors/403',  'ErrorController::forbidden',   ['as' => 'error.403']);
$routes->get('errors/500',  'ErrorController::serverError', ['as' => 'error.500']);

// =============================================================================
//  CATATAN UNTUK MODUL BERIKUTNYA
// =============================================================================
// Ketika menambahkan modul baru, cukup tambahkan satu baris:
//   require APPPATH . 'Modules/NamaModul/Config/Routes.php';
// Dan daftarkan namespace-nya di Config/Autoload.php