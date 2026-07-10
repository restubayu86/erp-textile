<?php

$routes->group('warehouse', [
    'namespace' => 'App\Modules\Warehouse\Controllers',
    'filter'    => 'shield',
], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Stocks
    $routes->get('stocks/view',              'StockController::index');
    $routes->get('stocks/stock-card',        'StockController::stockCard');
    $routes->get('stocks/position',          'StockController::position');
    $routes->get('stocks/receipt',           'StockController::receipt');
    $routes->post('stocks/receipt/store',    'StockController::storeReceipt');
    $routes->get('stocks/issue',             'StockController::issue');
    $routes->post('stocks/issue/store',      'StockController::storeIssue');
    $routes->get('stocks/adjustment',        'StockController::adjustment');
    $routes->post('stocks/adjustment/store', 'StockController::storeAdjustment');

    // Formulations (endpoint, form kompleks)
    $routes->get('formulations',                 'FormulationController::index');
    $routes->get('formulations/datatables',      'FormulationController::datatables');
    $routes->get('formulations/create',          'FormulationController::create');
    $routes->post('formulations/store',          'FormulationController::store');
    $routes->get('formulations/(:num)',          'FormulationController::show/$1');
    $routes->get('formulations/(:num)/edit',     'FormulationController::edit/$1');
    $routes->post('formulations/(:num)/update',  'FormulationController::update/$1');
    $routes->post('formulations/(:num)/delete',  'FormulationController::delete/$1');
    $routes->get('formulations/categories',      'FormulationController::categories');

    // ============================================================
    // Master: Chemical Categories (modal)
    // ============================================================
    $routes->get('master/chemical-categories',                    'ChemicalCategoryController::index');
    $routes->get('master/chemical-categories/trash',               'ChemicalCategoryController::trash');
    $routes->get('master/chemical-categories/datatables',          'ChemicalCategoryController::datatables');
    $routes->get('master/chemical-categories/trash-datatables',    'ChemicalCategoryController::trashDatatables');
    $routes->get('master/chemical-categories/select2',             'ChemicalCategoryController::select2');
    $routes->get('master/chemical-categories/stats',               'ChemicalCategoryController::stats');
    $routes->get('master/chemical-categories/(:num)',               'ChemicalCategoryController::get/$1');
    $routes->post('master/chemical-categories/store',               'ChemicalCategoryController::store');
    $routes->post('master/chemical-categories/(:num)/delete',       'ChemicalCategoryController::delete/$1');
    $routes->post('master/chemical-categories/(:num)/restore',      'ChemicalCategoryController::restore/$1');
    $routes->post('master/chemical-categories/(:num)/force-delete', 'ChemicalCategoryController::forceDelete/$1');
    $routes->post('master/chemical-categories/empty-trash',         'ChemicalCategoryController::emptyTrash');

    // ============================================================
    // Master: Warehouses (modal)
    // ============================================================
    $routes->get('master/warehouses',                    'WarehouseController::index');
    $routes->get('master/warehouses/trash',               'WarehouseController::trash');
    $routes->get('master/warehouses/datatables',          'WarehouseController::datatables');
    $routes->get('master/warehouses/trash-datatables',    'WarehouseController::trashDatatables');
    $routes->get('master/warehouses/select2',             'WarehouseController::select2');
    $routes->get('master/warehouses/stats',                'WarehouseController::stats');
    $routes->get('master/warehouses/(:num)',               'WarehouseController::get/$1');
    $routes->post('master/warehouses/store',               'WarehouseController::store');
    $routes->post('master/warehouses/(:num)/delete',       'WarehouseController::delete/$1');
    $routes->post('master/warehouses/(:num)/restore',      'WarehouseController::restore/$1');
    $routes->post('master/warehouses/(:num)/force-delete', 'WarehouseController::forceDelete/$1');
    $routes->post('master/warehouses/empty-trash',         'WarehouseController::emptyTrash');

    // ============================================================
    // Master: Chemicals (modal — chemical utama + varian)
    // ============================================================
    $routes->get('master/chemicals',                    'ChemicalController::index');
    $routes->get('master/chemicals/trash',               'ChemicalController::trash');
    $routes->get('master/chemicals/datatables',          'ChemicalController::datatables');
    $routes->get('master/chemicals/trash-datatables',    'ChemicalController::trashDatatables');
    $routes->get('master/chemicals/select2',             'ChemicalController::select2');
    $routes->get('master/chemicals/stats',                'ChemicalController::stats');
    $routes->get('master/chemicals/(:num)',               'ChemicalController::get/$1');
    $routes->post('master/chemicals/store',               'ChemicalController::store');
    $routes->post('master/chemicals/(:num)/delete',       'ChemicalController::delete/$1');
    $routes->post('master/chemicals/(:num)/restore',      'ChemicalController::restore/$1');
    $routes->post('master/chemicals/(:num)/force-delete', 'ChemicalController::forceDelete/$1');
    $routes->post('master/chemicals/empty-trash',         'ChemicalController::emptyTrash');

    // Varian bahan kimia (CRUD lewat modal per baris)
    $routes->get('master/chemicals/variants/next-code',                'ChemicalController::nextCode');
    $routes->get('master/chemicals/(:num)/variants',                  'ChemicalController::getVariants/$1');
    $routes->post('master/chemicals/(:num)/variants/store',           'ChemicalController::storeVariant/$1');
    $routes->post('master/chemicals/(:num)/variants/(:num)/update',   'ChemicalController::updateVariant/$1/$2');
    $routes->post('master/chemicals/variants/(:num)/delete',          'ChemicalController::deleteVariant/$1');
    $routes->post('master/chemicals/(:num)/variants/(:num)/default',  'ChemicalController::setDefaultVariant/$1/$2');

    $routes->get('master/periods',                    'PeriodController::index');
    $routes->get('master/periods/trash',               'PeriodController::trash');
    $routes->get('master/periods/datatables',          'PeriodController::datatables');
    $routes->get('master/periods/trash-datatables',    'PeriodController::trashDatatables');
    $routes->get('master/periods/select2',             'PeriodController::select2');
    $routes->get('master/periods/stats',                'PeriodController::stats');
    $routes->get('master/periods/(:num)',               'PeriodController::get/$1');
    $routes->post('master/periods/store',               'PeriodController::store');
    $routes->post('master/periods/(:num)/delete',       'PeriodController::delete/$1');
    $routes->post('master/periods/(:num)/restore',      'PeriodController::restore/$1');
    $routes->post('master/periods/(:num)/force-delete', 'PeriodController::forceDelete/$1');
    $routes->post('master/periods/empty-trash',         'PeriodController::emptyTrash');

    // Aksi bisnis khusus periode
    $routes->post('master/periods/(:num)/set-current', 'PeriodController::setCurrent/$1');
    $routes->post('master/periods/(:num)/close',        'PeriodController::closePeriod/$1');
});
