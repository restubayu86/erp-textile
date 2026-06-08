<?php

$routes->group('hrm', [
    'namespace' => 'App\Modules\HRM\Controllers',
    'filter'    => 'shield',
], function ($routes) {
    // Departments
    $routes->get('departments',              'DepartmentController::index');
    $routes->get('departments/datatables',   'DepartmentController::datatables');
    $routes->get('departments/(:num)',       'DepartmentController::getById/$1');
    $routes->post('departments/store',        'DepartmentController::store');
    $routes->post('departments/(:num)/delete','DepartmentController::delete/$1');

    // Positions
    $routes->get('positions',               'PositionController::index');
    $routes->get('positions/datatables',    'PositionController::datatables');
    $routes->get('positions/(:num)',        'PositionController::getById/$1');
    $routes->post('positions/store',         'PositionController::store');
    $routes->post('positions/(:num)/delete','PositionController::delete/$1');

    // Employees (endpoint, form kompleks)
    $routes->get('employees',                    'EmployeeController::index');
    $routes->get('employees/datatables',         'EmployeeController::datatables');
    $routes->get('employees/create',             'EmployeeController::create');
    $routes->post('employees/store',             'EmployeeController::store');
    $routes->get('employees/(:num)',            'EmployeeController::show/$1');
    $routes->get('employees/(:num)/edit',       'EmployeeController::edit/$1');
    $routes->post('employees/(:num)/update',    'EmployeeController::update/$1');
    $routes->post('employees/(:num)/delete',    'EmployeeController::delete/$1');
});
