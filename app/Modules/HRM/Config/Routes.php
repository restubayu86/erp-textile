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

// =============================================================================
//  HRM — Employees Routes
//  Prefix  : /hrm/employees
//  Filter  : shield
//  NS      : App\Modules\HRM\Controllers
// =============================================================================

$routes->group('hrm', ['namespace' => 'App\Modules\HRM\Controllers', 'filter' => 'shield'], function ($routes) {

    // ── Pages ──────────────────────────────────────────────────────────────
    $routes->get('employees',                           'EmployeeController::index',          ['as' => 'hrm.employees']);
    $routes->get('employees/trash',                     'EmployeeController::trash',          ['as' => 'hrm.employees.trash']);
    $routes->get('employees/create',                    'EmployeeController::create',         ['as' => 'hrm.employees.create']);
    $routes->get('employees/edit/(:num)',                'EmployeeController::edit/$1',        ['as' => 'hrm.employees.edit']);
    $routes->get('employees/show/(:num)',                'EmployeeController::show/$1',        ['as' => 'hrm.employees.show']);

    // ── DataTable endpoints ────────────────────────────────────────────────
    $routes->get('employees/datatables',                'EmployeeController::datatables');
    $routes->get('employees/trash-datatables',          'EmployeeController::trashDatatables');

    // ── Stats & Select2 ────────────────────────────────────────────────────
    $routes->get('employees/stats',                     'EmployeeController::stats');
    $routes->get('employees/select2',                   'EmployeeController::select2');

    // ── Lookups ────────────────────────────────────────────────────────────
    $routes->get('employees/by-position/(:num)',        'EmployeeController::getByPosition/$1');
    $routes->get('employees/by-department/(:num)',      'EmployeeController::getByDepartment/$1');
    $routes->get('employees/by-shift/(:alpha)',         'EmployeeController::getByShift/$1');
    $routes->get('employees/by-work-area',              'EmployeeController::getByWorkArea');

    // ── Export & Print ─────────────────────────────────────────────────────
    $routes->get('employees/export',                    'EmployeeController::export');
    $routes->get('employees/print',                     'EmployeeController::print');

    // ── AJAX CRUD ──────────────────────────────────────────────────────────
    $routes->get('employees/get/(:num)',                'EmployeeController::get/$1');
    $routes->post('employees/store',                    'EmployeeController::store',          ['as' => 'hrm.employees.store']);
    $routes->post('employees/delete/(:num)',            'EmployeeController::delete/$1');
    $routes->post('employees/restore/(:num)',           'EmployeeController::restore/$1');
    $routes->post('employees/force-delete/(:num)',      'EmployeeController::forceDelete/$1');
    $routes->post('employees/empty-trash',              'EmployeeController::emptyTrash');
    $routes->post('employees/check-unique',             'EmployeeController::checkUnique');

    // ── Photo ──────────────────────────────────────────────────────────────
    $routes->post('employees/upload-photo/(:num)',      'EmployeeController::uploadPhoto/$1');
    $routes->post('employees/delete-photo/(:num)',      'EmployeeController::deletePhoto/$1');
});
