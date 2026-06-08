<?php

$routes->group('warehouse', [
    'namespace' => 'App\Modules\Warehouse\Controllers',
    'filter'    => 'shield',
], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Stocks
    $routes->get('stocks/view',             'StockController::index');
    $routes->get('stocks/stock-card',       'StockController::stockCard');
    $routes->get('stocks/position',         'StockController::position');
    $routes->get('stocks/receipt',          'StockController::receipt');
    $routes->post('stocks/receipt/store',   'StockController::storeReceipt');
    $routes->get('stocks/issue',            'StockController::issue');
    $routes->post('stocks/issue/store',     'StockController::storeIssue');
    $routes->get('stocks/adjustment',       'StockController::adjustment');
    $routes->post('stocks/adjustment/store','StockController::storeAdjustment');

    // Formulations (endpoint, form kompleks)
    $routes->get('formulations',                  'FormulationController::index');
    $routes->get('formulations/datatables',       'FormulationController::datatables');
    $routes->get('formulations/create',           'FormulationController::create');
    $routes->post('formulations/store',           'FormulationController::store');
    $routes->get('formulations/(:num)',          'FormulationController::show/$1');
    $routes->get('formulations/(:num)/edit',     'FormulationController::edit/$1');
    $routes->post('formulations/(:num)/update',  'FormulationController::update/$1');
    $routes->post('formulations/(:num)/delete',  'FormulationController::delete/$1');
    $routes->get('formulations/categories',       'FormulationController::categories');

    // Master: Chemicals (endpoint, form cukup kompleks)
    $routes->get('master/chemicals',              'ChemicalController::index');
    $routes->get('master/chemicals/datatables',   'ChemicalController::datatables');
    $routes->get('master/chemicals/create',       'ChemicalController::create');
    $routes->post('master/chemicals/store',       'ChemicalController::store');
    $routes->get('master/chemicals/(:num)',      'ChemicalController::show/$1');
    $routes->get('master/chemicals/(:num)/edit', 'ChemicalController::edit/$1');
    $routes->post('master/chemicals/(:num)/update','ChemicalController::update/$1');
    $routes->post('master/chemicals/(:num)/delete','ChemicalController::delete/$1');

    // Master: Chemical Categories (modal)
    $routes->get('master/chemical-categories',            'ChemicalCategoryController::index');
    $routes->get('master/chemical-categories/datatables', 'ChemicalCategoryController::datatables');
    $routes->get('master/chemical-categories/(:num)',    'ChemicalCategoryController::getById/$1');
    $routes->post('master/chemical-categories/store',     'ChemicalCategoryController::store');
    $routes->post('master/chemical-categories/(:num)/delete','ChemicalCategoryController::delete/$1');

    // Master: Warehouses (modal)
    $routes->get('master/warehouses',             'WarehouseController::index');
    $routes->get('master/warehouses/datatables',  'WarehouseController::datatables');
    $routes->get('master/warehouses/(:num)',     'WarehouseController::getById/$1');
    $routes->post('master/warehouses/store',      'WarehouseController::store');
    $routes->post('master/warehouses/(:num)/delete','WarehouseController::delete/$1');
});
