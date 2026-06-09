<?php

// =============================================================================
//  HRM — Departments Routes
//  Prefix  : /hrm/departments
//  Filter  : shield
//  NS      : App\Modules\HRM\Controllers
// =============================================================================

$routes->group('hrm', ['namespace' => 'App\Modules\HRM\Controllers', 'filter' => 'shield'], function ($routes) {

    // ── Departments ──────────────────────────────────────────────────────────
    $routes->get('departments',                          'DepartmentController::index',        ['as' => 'hrm.departments']);
    $routes->get('departments/trash',                    'DepartmentController::trash',        ['as' => 'hrm.departments.trash']);

    // DataTable endpoints
    $routes->get('departments/datatables',               'DepartmentController::datatables');
    $routes->get('departments/trash-datatables',         'DepartmentController::trashDatatables');

    // Stats & Select2
    $routes->get('departments/stats',                    'DepartmentController::stats');
    $routes->get('departments/select2',                  'DepartmentController::select2');

    // AJAX CRUD
    $routes->get('departments/get/(:num)',               'DepartmentController::get/$1');
    $routes->post('departments/store',                   'DepartmentController::store',        ['as' => 'hrm.departments.store']);
    $routes->post('departments/delete/(:num)',           'DepartmentController::delete/$1');
    $routes->post('departments/restore/(:num)',          'DepartmentController::restore/$1');
    $routes->post('departments/force-delete/(:num)',     'DepartmentController::forceDelete/$1');
    $routes->post('departments/empty-trash',             'DepartmentController::emptyTrash');
    $routes->post('departments/check-unique',            'DepartmentController::checkUnique');
});

// =============================================================================
//  HRM — Positions Routes
//  Prefix  : /hrm/positions
//  Filter  : shield
//  NS      : App\Modules\HRM\Controllers
// =============================================================================

$routes->group('hrm', ['namespace' => 'App\Modules\HRM\Controllers', 'filter' => 'shield'], function ($routes) {

    // ── Positions ──────────────────────────────────────────────────────────
    $routes->get('positions',                          'PositionController::index',        ['as' => 'hrm.positions']);
    $routes->get('positions/trash',                    'PositionController::trash',        ['as' => 'hrm.positions.trash']);

    // DataTable endpoints
    $routes->get('positions/datatables',               'PositionController::datatables');
    $routes->get('positions/trash-datatables',         'PositionController::trashDatatables');

    // Stats & Select2
    $routes->get('positions/stats',                    'PositionController::stats');
    $routes->get('positions/select2',                  'PositionController::select2');

    // AJAX CRUD
    $routes->get('positions/get/(:num)',               'PositionController::get/$1');
    $routes->post('positions/store',                   'PositionController::store',        ['as' => 'hrm.positions.store']);
    $routes->post('positions/delete/(:num)',           'PositionController::delete/$1');
    $routes->post('positions/restore/(:num)',          'PositionController::restore/$1');
    $routes->post('positions/force-delete/(:num)',     'PositionController::forceDelete/$1');
    $routes->post('positions/empty-trash',             'PositionController::emptyTrash');
    $routes->post('positions/check-unique',            'PositionController::checkUnique');
});
