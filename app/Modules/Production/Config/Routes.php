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
    // Master Data
    // =============================================
    $routes->group('master', function ($routes) {

        // ── Machines ────────────────────────────────────────────
        $routes->get('machines',                      'MachineController::index');
        $routes->get('machines/trash',                 'MachineController::trash');

        $routes->get('machines/datatables',            'MachineController::datatables');
        $routes->get('machines/trash-datatables',      'MachineController::trashDatatables');

        $routes->get('machines/stats',                 'MachineController::stats');
        $routes->get('machines/select2',               'MachineController::select2');
        $routes->post('machines/check-unique',         'MachineController::checkUnique');

        $routes->get('machines/get/(:num)',            'MachineController::get/$1');
        $routes->post('machines/store',                'MachineController::store');
        $routes->post('machines/delete/(:num)',        'MachineController::delete/$1');
        $routes->post('machines/restore/(:num)',       'MachineController::restore/$1');
        $routes->post('machines/force-delete/(:num)',  'MachineController::forceDelete/$1');
        $routes->post('machines/empty-trash',          'MachineController::emptyTrash');

        // ── Fabrics ─────────────────────────────────────────────
        $routes->get('fabrics',                        'FabricController::index');
        $routes->get('fabrics/trash',                   'FabricController::trash');

        $routes->get('fabrics/datatables',              'FabricController::datatables');
        $routes->get('fabrics/trash-datatables',        'FabricController::trashDatatables');

        $routes->get('fabrics/stats',                   'FabricController::stats');
        $routes->get('fabrics/select2',                 'FabricController::select2');
        $routes->post('fabrics/check-unique',           'FabricController::checkUnique');

        $routes->get('fabrics/get/(:num)',              'FabricController::get/$1');
        $routes->post('fabrics/store',                  'FabricController::store');
        $routes->post('fabrics/delete/(:num)',          'FabricController::delete/$1');
        $routes->post('fabrics/restore/(:num)',         'FabricController::restore/$1');
        $routes->post('fabrics/force-delete/(:num)',    'FabricController::forceDelete/$1');
        $routes->post('fabrics/empty-trash',            'FabricController::emptyTrash');

        // Halaman detail — taruh PALING BAWAH supaya tidak menabrak
        // path statis di atas (trash, datatables, stats, dst)
        $routes->get('fabrics/(:num)',                  'FabricController::show/$1');

        // ── Flow Processes ───────────────────────────────────────
        // Tidak ada halaman index/trash sendiri — diakses dari
        // halaman detail Fabric (production/master/fabrics/{id})
        $routes->get('flow-processes/datatables',       'FlowProcessController::datatables');
        $routes->get('flow-processes/process-names',    'FlowProcessController::processNames');

        $routes->get('flow-processes/get/(:num)',       'FlowProcessController::get/$1');
        $routes->post('flow-processes/store',           'FlowProcessController::store');
        $routes->post('flow-processes/delete/(:num)',   'FlowProcessController::delete/$1');
    });
});
