<?php

$routes->group('production', [
    'namespace' => 'App\Modules\Production\Controllers',
    'filter'    => 'shield',
], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Work Orders (endpoint, form kompleks)
    $routes->get('work-orders',                   'WorkOrderController::index');
    $routes->get('work-orders/datatables',        'WorkOrderController::datatables');
    $routes->get('work-orders/create',            'WorkOrderController::create');
    $routes->post('work-orders/store',            'WorkOrderController::store');
    $routes->get('work-orders/(:num)',           'WorkOrderController::show/$1');
    $routes->get('work-orders/(:num)/edit',      'WorkOrderController::edit/$1');
    $routes->post('work-orders/(:num)/update',   'WorkOrderController::update/$1');
    $routes->post('work-orders/(:num)/delete',   'WorkOrderController::delete/$1');
    $routes->post('work-orders/(:num)/confirm',  'WorkOrderController::confirm/$1');

    // Checksheets
    $routes->get('checksheets',                   'ChecksheetController::index');
    $routes->get('checksheets/datatables',        'ChecksheetController::datatables');

    // Reports
    $routes->get('reports',                       'ReportController::index');

    // =============================================
    // FIXED: Machine Routes
    // =============================================
    // Main endpoints (without "master/" prefix to match JS)
    $routes->get('machines',                      'MachineController::index');
    $routes->get('machines/datatables',           'MachineController::datatables');
    $routes->get('machines/stats',                'MachineController::stats');  // NEW
    $routes->get('machines/get/(:num)',           'MachineController::getById/$1');
    $routes->post('machines/store',               'MachineController::store');
    $routes->post('machines/delete/(:num)',      'MachineController::delete/$1');

    // Trash route
    $routes->get('machines/trash',                'MachineController::trash');

    // Keep master routes for backward compatibility if needed
    $routes->group('master', function ($routes) {
        $routes->get('machines',                  'MachineController::index');
        $routes->get('machines/datatables',       'MachineController::datatables');
        $routes->get('machines/(:num)',           'MachineController::getById/$1');
        $routes->post('machines/store',           'MachineController::store');
        $routes->post('machines/(:num)/delete',   'MachineController::delete/$1');

        $routes->get('machine-types',             'MachineTypeController::index');
        $routes->get('machine-types/datatables',  'MachineTypeController::datatables');
        $routes->get('machine-types/(:num)',      'MachineTypeController::getById/$1');
        $routes->post('machine-types/store',      'MachineTypeController::store');
        $routes->post('machine-types/(:num)/delete', 'MachineTypeController::delete/$1');
    });
});
