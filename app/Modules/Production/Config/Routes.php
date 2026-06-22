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

        // ── Designs ─────────────────────────────────────────────
        $routes->get('designs',                        'DesignController::index');
        $routes->get('designs/trash',                   'DesignController::trash');

        $routes->get('designs/datatables',              'DesignController::datatables');
        $routes->get('designs/trash-datatables',        'DesignController::trashDatatables');

        $routes->get('designs/stats',                   'DesignController::stats');
        $routes->get('designs/select2',                 'DesignController::select2');
        $routes->post('designs/check-unique',           'DesignController::checkUnique');

        $routes->get('designs/get/(:num)',              'DesignController::get/$1');
        $routes->post('designs/store',                  'DesignController::store');
        $routes->post('designs/delete/(:num)',          'DesignController::delete/$1');
        $routes->post('designs/restore/(:num)',         'DesignController::restore/$1');
        $routes->post('designs/force-delete/(:num)',    'DesignController::forceDelete/$1');
        $routes->post('designs/empty-trash',            'DesignController::emptyTrash');

        // Halaman detail — taruh PALING BAWAH supaya tidak menabrak
        // path statis di atas (trash, datatables, stats, dst)
        $routes->get('designs/(:num)',                  'DesignController::show/$1');

        // ── Flow Processes ───────────────────────────────────────
        // Tidak ada halaman index/trash sendiri — diakses dari
        // halaman detail Design (production/master/designs/{id})
        $routes->get('flow-processes/datatables',       'FlowProcessController::datatables');
        $routes->get('flow-processes/process-names',    'FlowProcessController::processNames');
        $routes->get('flow-processes/chemical-codes',   'FlowProcessController::chemicalCodes');

        $routes->get('flow-processes/get/(:num)',       'FlowProcessController::get/$1');
        $routes->post('flow-processes/store',           'FlowProcessController::store');
        $routes->post('flow-processes/delete/(:num)',   'FlowProcessController::delete/$1');
    });
});
