<?php

$routes->group('warehouse', [
    'namespace' => 'App\Modules\Warehouse\Controllers',
    'filter'    => 'shield',
], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Stocks
    $routes->get('stocks/view',              'StockController::index');
    $routes->get('stocks/stock-card',        'StockController::stockCard');
    $routes->get('stocks/stock-card/data',   'StockController::stockCardData');
    $routes->get('stocks/position',          'StockController::position');
    $routes->get('stocks/position/grid',     'StockController::positionGrid');
    $routes->get('stocks/position/combined', 'StockController::positionCombinedGrid');
    $routes->get('stocks/position/breakdown', 'StockController::positionBreakdown');
    $routes->get('stocks/receipt',           'StockController::receipt');
    $routes->post('stocks/receipt/store',    'StockController::storeReceipt');
    $routes->get('stocks/receipt/recent',    'StockController::receiptRecent');
    $routes->post('stocks/receipt/delete',   'StockController::receiptDelete');
    $routes->get('stocks/issue',             'StockController::issue');
    $routes->post('stocks/issue/store',      'StockController::storeIssue');
    $routes->get('stocks/adjustment',        'StockController::adjustment');
    $routes->post('stocks/adjustment/store', 'StockController::storeAdjustment');

    // ============================================================
    // FORMULATIONS
    // ============================================================
    $routes->get('formulations',                 'FormulationController::index');
    $routes->get('formulations/datatables',      'FormulationController::datatables');
    $routes->get('formulations/create',          'FormulationController::create');
    $routes->post('formulations/store',          'FormulationController::store');
    $routes->get('formulations/(:num)',          'FormulationController::show/$1');
    $routes->get('formulations/(:num)/edit',     'FormulationController::edit/$1');
    $routes->post('formulations/(:num)/update',  'FormulationController::update/$1');
    $routes->post('formulations/(:num)/delete',  'FormulationController::delete/$1');
    $routes->get('formulations/categories',      'FormulationController::categories');
    $routes->get('formulations/sub-process-types', 'FormulationController::subProcessTypes');
    $routes->get('formulations/trash',           'FormulationController::trash');
    $routes->get('formulations/trash-datatables', 'FormulationController::trashDatatables');
    $routes->get('formulations/select2',         'FormulationController::select2');
    $routes->get('formulations/groups/select2',  'FormulationController::groupsSelect2');
    $routes->get('formulations/stats',           'FormulationController::stats');
    $routes->post('formulations/(:num)/restore', 'FormulationController::restore/$1');
    $routes->post('formulations/(:num)/force-delete', 'FormulationController::forceDelete/$1');
    $routes->post('formulations/empty-trash',    'FormulationController::emptyTrash');
    $routes->post('formulations/generate-code-suggestion', 'FormulationController::generateCodeSuggestion');
    $routes->post('formulations/check-name',     'FormulationController::checkName');

    // Versioning
    $routes->get('formulations/(:num)/versions',                       'FormulationController::versions/$1');
    $routes->post('formulations/(:num)/versions/(:num)/activate',      'FormulationController::activateVersion/$1/$2');
    $routes->get('formulations/(:num)/versions/(:num)/detail',         'FormulationController::getVersionDetail/$1/$2');

    // Comparison
    $routes->get('formulations/compare-versions',       'FormulationController::compareVersions');
    $routes->get('formulations/compare-formulations',   'FormulationController::compareFormulations');
    $routes->post('formulations/(:num)/mark-used',      'FormulationController::markUsed/$1');

    // ============================================================
    // Master: Chemical Categories
    // ============================================================
    $routes->get('master/chemical-categories',                    'ChemicalCategoryController::index');
    $routes->get('master/chemical-categories/trash',               'ChemicalCategoryController::trash');
    $routes->get('master/chemical-categories/datatables',          'ChemicalCategoryController::datatables');
    $routes->get('master/chemical-categories/trash-datatables',    'ChemicalCategoryController::trashDatatables');
    $routes->get('master/chemical-categories/select2',             'ChemicalCategoryController::select2');
    $routes->get('master/chemical-categories/stats',                'ChemicalCategoryController::stats');
    $routes->get('master/chemical-categories/(:num)',               'ChemicalCategoryController::get/$1');
    $routes->post('master/chemical-categories/store',               'ChemicalCategoryController::store');
    $routes->post('master/chemical-categories/(:num)/delete',       'ChemicalCategoryController::delete/$1');
    $routes->post('master/chemical-categories/(:num)/restore',      'ChemicalCategoryController::restore/$1');
    $routes->post('master/chemical-categories/(:num)/force-delete', 'ChemicalCategoryController::forceDelete/$1');
    $routes->post('master/chemical-categories/empty-trash',         'ChemicalCategoryController::emptyTrash');

    // ============================================================
    // Master: Warehouses
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
    // Master: Chemicals
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

    // Varian
    $routes->get('master/chemicals/variants/next-code',                'ChemicalController::nextCode');
    $routes->get('master/chemicals/(:num)/variants',                  'ChemicalController::getVariants/$1');
    $routes->post('master/chemicals/(:num)/variants/store',           'ChemicalController::storeVariant/$1');
    $routes->post('master/chemicals/(:num)/variants/(:num)/update',   'ChemicalController::updateVariant/$1/$2');
    $routes->post('master/chemicals/variants/(:num)/delete',          'ChemicalController::deleteVariant/$1');
    $routes->post('master/chemicals/(:num)/variants/(:num)/default',  'ChemicalController::setDefaultVariant/$1/$2');

    // Periods
    $routes->get('master/periods',                    'PeriodController::index');
    $routes->get('master/periods/by-month',            'PeriodController::byMonth');
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
    $routes->post('master/periods/(:num)/set-current', 'PeriodController::setCurrent/$1');
    $routes->post('master/periods/(:num)/close',        'PeriodController::closePeriod/$1');

    // Stock Opening
    // Stock Opening — Kimia
    $routes->get('stocks/opening',               'ChemicalStockOpeningController::index');
    $routes->get('stocks/opening/grid',          'ChemicalStockOpeningController::grid');
    $routes->get('stocks/opening/combined',      'ChemicalStockOpeningController::combinedGrid');
    $routes->get('stocks/opening/breakdown',     'ChemicalStockOpeningController::breakdown');
    $routes->post('stocks/opening/store',        'ChemicalStockOpeningController::store');
    $routes->get('stocks/opening/status',        'ChemicalStockOpeningController::status');
    $routes->get('stocks/opening/pull-previous', 'ChemicalStockOpeningController::pullPrevious');

    // Stock Opening — Formulasi
    $routes->get('stocks/formulation-opening',               'FormulationStockOpeningController::index');
    $routes->get('stocks/formulation-opening/grid',          'FormulationStockOpeningController::grid');
    $routes->get('stocks/formulation-opening/combined',      'FormulationStockOpeningController::combinedGrid');
    $routes->get('stocks/formulation-opening/breakdown',     'FormulationStockOpeningController::breakdown');
    $routes->post('stocks/formulation-opening/store',        'FormulationStockOpeningController::store');
    $routes->get('stocks/formulation-opening/status',        'FormulationStockOpeningController::status');
    $routes->get('stocks/formulation-opening/pull-previous', 'FormulationStockOpeningController::pullPrevious');

    // Stock Opname — Kimia
    $routes->get('stocks/opname',           'ChemicalStockOpnameController::index');
    $routes->get('stocks/opname/grid',      'ChemicalStockOpnameController::grid');
    $routes->get('stocks/opname/combined',  'ChemicalStockOpnameController::combinedGrid');
    $routes->get('stocks/opname/breakdown', 'ChemicalStockOpnameController::breakdown');
    $routes->post('stocks/opname/store',    'ChemicalStockOpnameController::store');

    // Stock Opname — Formulasi
    $routes->get('stocks/formulation-opname',           'FormulationStockOpnameController::index');
    $routes->get('stocks/formulation-opname/grid',      'FormulationStockOpnameController::grid');
    $routes->get('stocks/formulation-opname/combined',  'FormulationStockOpnameController::combinedGrid');
    $routes->get('stocks/formulation-opname/breakdown', 'FormulationStockOpnameController::breakdown');
    $routes->post('stocks/formulation-opname/store',    'FormulationStockOpnameController::store');

    // Stok Akhir IFS — Kimia
    $routes->get('stocks/ifs',           'ChemicalStockIfsController::index');
    $routes->get('stocks/ifs/grid',      'ChemicalStockIfsController::grid');
    $routes->get('stocks/ifs/combined',  'ChemicalStockIfsController::combinedGrid');
    $routes->get('stocks/ifs/breakdown', 'ChemicalStockIfsController::breakdown');
    $routes->post('stocks/ifs/store',    'ChemicalStockIfsController::store');
});
