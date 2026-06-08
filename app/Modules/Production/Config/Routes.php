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

    // Master: Machines (modal)
    $routes->get('master/machines',               'MachineController::index');
    $routes->get('master/machines/datatables',    'MachineController::datatables');
    $routes->get('master/machines/(:num)',       'MachineController::getById/$1');
    $routes->post('master/machines/store',        'MachineController::store');
    $routes->post('master/machines/(:num)/delete','MachineController::delete/$1');

    // Master: Machine Types (modal)
    $routes->get('master/machine-types',          'MachineTypeController::index');
    $routes->get('master/machine-types/datatables','MachineTypeController::datatables');
    $routes->get('master/machine-types/(:num)',  'MachineTypeController::getById/$1');
    $routes->post('master/machine-types/store',   'MachineTypeController::store');
    $routes->post('master/machine-types/(:num)/delete','MachineTypeController::delete/$1');
});
