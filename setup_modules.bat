@echo off
chcp 65001 >nul
setlocal EnableDelayedExpansion

echo ============================================================
echo  ERP Textile Dyeing ^& Finishing
echo  Setup Struktur Folder Modul + File Placeholder
echo  v2.0 - disesuaikan dengan struktur Phoenix Admin erp3a
echo ============================================================
echo.

:: ─────────────────────────────────────────────────────────────
::  VALIDASI ROOT PROYEK
:: ─────────────────────────────────────────────────────────────
set /p PROJECT_ROOT=Path root proyek CI4 (contoh: C:\xampp\htdocs\erp-textile): 

:: Hapus trailing backslash jika ada
if "%PROJECT_ROOT:~-1%"=="\" set PROJECT_ROOT=%PROJECT_ROOT:~0,-1%

echo.
if not exist "%PROJECT_ROOT%" (
    echo [ERROR] Folder tidak ditemukan: %PROJECT_ROOT%
    pause & exit /b 1
)
if not exist "%PROJECT_ROOT%\app" (
    echo [ERROR] Bukan root proyek CI4 yang valid (folder app\ tidak ada^)
    pause & exit /b 1
)
if not exist "%PROJECT_ROOT%\spark" (
    echo [ERROR] File spark tidak ditemukan. Pastikan CI4 sudah di-install.
    pause & exit /b 1
)

echo [OK] Proyek ditemukan: %PROJECT_ROOT%
echo.

:: ─────────────────────────────────────────────────────────────
::  VARIABEL PATH
:: ─────────────────────────────────────────────────────────────
set APP=%PROJECT_ROOT%\app
set MOD=%APP%\Modules
set VIEWS=%APP%\Views
set CTRL=%APP%\Controllers
set HELPERS=%APP%\Helpers
set SEEDS=%APP%\Database\Seeds
set ERRORS=%APP%\Views\errors
set AUTH_VIEWS=%APP%\Views\auth
set TEMPLATES=%APP%\Views\templates

:: ════════════════════════════════════════════════════════════
::  [1/6] MODUL HRM
:: ════════════════════════════════════════════════════════════
echo [1/6] Membuat modul HRM...

mkdir "%MOD%\HRM\Controllers"             2>nul
mkdir "%MOD%\HRM\Models"                  2>nul
mkdir "%MOD%\HRM\Config"                  2>nul
mkdir "%MOD%\HRM\Database\Migrations"     2>nul
mkdir "%MOD%\HRM\Views\departments"       2>nul
mkdir "%MOD%\HRM\Views\positions"         2>nul
mkdir "%MOD%\HRM\Views\employees"         2>nul

> "%MOD%\HRM\Config\Routes.php" (
    echo ^<?php
    echo.
    echo $routes-^>group^('hrm', [
    echo     'namespace' =^> 'App\Modules\HRM\Controllers',
    echo     'filter'    =^> 'shield',
    echo ], function ^($routes^) {
    echo     // Departments
    echo     $routes-^>get^('departments',              'DepartmentController::index'^);
    echo     $routes-^>get^('departments/datatables',   'DepartmentController::datatables'^);
    echo     $routes-^>get^('departments/(:num^)',       'DepartmentController::getById/$1'^);
    echo     $routes-^>post^('departments/store',        'DepartmentController::store'^);
    echo     $routes-^>post^('departments/(:num^)/delete','DepartmentController::delete/$1'^);
    echo.
    echo     // Positions
    echo     $routes-^>get^('positions',               'PositionController::index'^);
    echo     $routes-^>get^('positions/datatables',    'PositionController::datatables'^);
    echo     $routes-^>get^('positions/(:num^)',        'PositionController::getById/$1'^);
    echo     $routes-^>post^('positions/store',         'PositionController::store'^);
    echo     $routes-^>post^('positions/(:num^)/delete','PositionController::delete/$1'^);
    echo.
    echo     // Employees (endpoint, form kompleks^)
    echo     $routes-^>get^('employees',                    'EmployeeController::index'^);
    echo     $routes-^>get^('employees/datatables',         'EmployeeController::datatables'^);
    echo     $routes-^>get^('employees/create',             'EmployeeController::create'^);
    echo     $routes-^>post^('employees/store',             'EmployeeController::store'^);
    echo     $routes-^>get^('employees/(:num^)',            'EmployeeController::show/$1'^);
    echo     $routes-^>get^('employees/(:num^)/edit',       'EmployeeController::edit/$1'^);
    echo     $routes-^>post^('employees/(:num^)/update',    'EmployeeController::update/$1'^);
    echo     $routes-^>post^('employees/(:num^)/delete',    'EmployeeController::delete/$1'^);
    echo }^);
)

echo     [OK] HRM

:: ════════════════════════════════════════════════════════════
::  [2/6] MODUL PRODUCTION
:: ════════════════════════════════════════════════════════════
echo [2/6] Membuat modul Production...

mkdir "%MOD%\Production\Controllers"          2>nul
mkdir "%MOD%\Production\Models"               2>nul
mkdir "%MOD%\Production\Config"               2>nul
mkdir "%MOD%\Production\Database\Migrations"  2>nul
mkdir "%MOD%\Production\Views\work_orders"    2>nul
mkdir "%MOD%\Production\Views\machines"       2>nul
mkdir "%MOD%\Production\Views\machine_types"  2>nul
mkdir "%MOD%\Production\Views\checksheets"    2>nul
mkdir "%MOD%\Production\Views\reports"        2>nul

> "%MOD%\Production\Config\Routes.php" (
    echo ^<?php
    echo.
    echo $routes-^>group^('production', [
    echo     'namespace' =^> 'App\Modules\Production\Controllers',
    echo     'filter'    =^> 'shield',
    echo ], function ^($routes^) {
    echo     $routes-^>get^('dashboard', 'DashboardController::index'^);
    echo.
    echo     // Work Orders (endpoint, form kompleks^)
    echo     $routes-^>get^('work-orders',                   'WorkOrderController::index'^);
    echo     $routes-^>get^('work-orders/datatables',        'WorkOrderController::datatables'^);
    echo     $routes-^>get^('work-orders/create',            'WorkOrderController::create'^);
    echo     $routes-^>post^('work-orders/store',            'WorkOrderController::store'^);
    echo     $routes-^>get^('work-orders/(:num^)',           'WorkOrderController::show/$1'^);
    echo     $routes-^>get^('work-orders/(:num^)/edit',      'WorkOrderController::edit/$1'^);
    echo     $routes-^>post^('work-orders/(:num^)/update',   'WorkOrderController::update/$1'^);
    echo     $routes-^>post^('work-orders/(:num^)/delete',   'WorkOrderController::delete/$1'^);
    echo     $routes-^>post^('work-orders/(:num^)/confirm',  'WorkOrderController::confirm/$1'^);
    echo.
    echo     // Checksheets
    echo     $routes-^>get^('checksheets',                   'ChecksheetController::index'^);
    echo     $routes-^>get^('checksheets/datatables',        'ChecksheetController::datatables'^);
    echo.
    echo     // Reports
    echo     $routes-^>get^('reports',                       'ReportController::index'^);
    echo.
    echo     // Master: Machines (modal^)
    echo     $routes-^>get^('master/machines',               'MachineController::index'^);
    echo     $routes-^>get^('master/machines/datatables',    'MachineController::datatables'^);
    echo     $routes-^>get^('master/machines/(:num^)',       'MachineController::getById/$1'^);
    echo     $routes-^>post^('master/machines/store',        'MachineController::store'^);
    echo     $routes-^>post^('master/machines/(:num^)/delete','MachineController::delete/$1'^);
    echo.
    echo     // Master: Machine Types (modal^)
    echo     $routes-^>get^('master/machine-types',          'MachineTypeController::index'^);
    echo     $routes-^>get^('master/machine-types/datatables','MachineTypeController::datatables'^);
    echo     $routes-^>get^('master/machine-types/(:num^)',  'MachineTypeController::getById/$1'^);
    echo     $routes-^>post^('master/machine-types/store',   'MachineTypeController::store'^);
    echo     $routes-^>post^('master/machine-types/(:num^)/delete','MachineTypeController::delete/$1'^);
    echo }^);
)

echo     [OK] Production

:: ════════════════════════════════════════════════════════════
::  [3/6] MODUL WAREHOUSE
:: ════════════════════════════════════════════════════════════
echo [3/6] Membuat modul Warehouse...

mkdir "%MOD%\Warehouse\Controllers"              2>nul
mkdir "%MOD%\Warehouse\Models"                   2>nul
mkdir "%MOD%\Warehouse\Config"                   2>nul
mkdir "%MOD%\Warehouse\Database\Migrations"      2>nul
mkdir "%MOD%\Warehouse\Views\chemicals"          2>nul
mkdir "%MOD%\Warehouse\Views\chemical_categories" 2>nul
mkdir "%MOD%\Warehouse\Views\formulations"       2>nul
mkdir "%MOD%\Warehouse\Views\stocks"             2>nul
mkdir "%MOD%\Warehouse\Views\warehouses"         2>nul

> "%MOD%\Warehouse\Config\Routes.php" (
    echo ^<?php
    echo.
    echo $routes-^>group^('warehouse', [
    echo     'namespace' =^> 'App\Modules\Warehouse\Controllers',
    echo     'filter'    =^> 'shield',
    echo ], function ^($routes^) {
    echo     $routes-^>get^('dashboard', 'DashboardController::index'^);
    echo.
    echo     // Stocks
    echo     $routes-^>get^('stocks/view',             'StockController::index'^);
    echo     $routes-^>get^('stocks/stock-card',       'StockController::stockCard'^);
    echo     $routes-^>get^('stocks/position',         'StockController::position'^);
    echo     $routes-^>get^('stocks/receipt',          'StockController::receipt'^);
    echo     $routes-^>post^('stocks/receipt/store',   'StockController::storeReceipt'^);
    echo     $routes-^>get^('stocks/issue',            'StockController::issue'^);
    echo     $routes-^>post^('stocks/issue/store',     'StockController::storeIssue'^);
    echo     $routes-^>get^('stocks/adjustment',       'StockController::adjustment'^);
    echo     $routes-^>post^('stocks/adjustment/store','StockController::storeAdjustment'^);
    echo.
    echo     // Formulations (endpoint, form kompleks^)
    echo     $routes-^>get^('formulations',                  'FormulationController::index'^);
    echo     $routes-^>get^('formulations/datatables',       'FormulationController::datatables'^);
    echo     $routes-^>get^('formulations/create',           'FormulationController::create'^);
    echo     $routes-^>post^('formulations/store',           'FormulationController::store'^);
    echo     $routes-^>get^('formulations/(:num^)',          'FormulationController::show/$1'^);
    echo     $routes-^>get^('formulations/(:num^)/edit',     'FormulationController::edit/$1'^);
    echo     $routes-^>post^('formulations/(:num^)/update',  'FormulationController::update/$1'^);
    echo     $routes-^>post^('formulations/(:num^)/delete',  'FormulationController::delete/$1'^);
    echo     $routes-^>get^('formulations/categories',       'FormulationController::categories'^);
    echo.
    echo     // Master: Chemicals (endpoint, form cukup kompleks^)
    echo     $routes-^>get^('master/chemicals',              'ChemicalController::index'^);
    echo     $routes-^>get^('master/chemicals/datatables',   'ChemicalController::datatables'^);
    echo     $routes-^>get^('master/chemicals/create',       'ChemicalController::create'^);
    echo     $routes-^>post^('master/chemicals/store',       'ChemicalController::store'^);
    echo     $routes-^>get^('master/chemicals/(:num^)',      'ChemicalController::show/$1'^);
    echo     $routes-^>get^('master/chemicals/(:num^)/edit', 'ChemicalController::edit/$1'^);
    echo     $routes-^>post^('master/chemicals/(:num^)/update','ChemicalController::update/$1'^);
    echo     $routes-^>post^('master/chemicals/(:num^)/delete','ChemicalController::delete/$1'^);
    echo.
    echo     // Master: Chemical Categories (modal^)
    echo     $routes-^>get^('master/chemical-categories',            'ChemicalCategoryController::index'^);
    echo     $routes-^>get^('master/chemical-categories/datatables', 'ChemicalCategoryController::datatables'^);
    echo     $routes-^>get^('master/chemical-categories/(:num^)',    'ChemicalCategoryController::getById/$1'^);
    echo     $routes-^>post^('master/chemical-categories/store',     'ChemicalCategoryController::store'^);
    echo     $routes-^>post^('master/chemical-categories/(:num^)/delete','ChemicalCategoryController::delete/$1'^);
    echo.
    echo     // Master: Warehouses (modal^)
    echo     $routes-^>get^('master/warehouses',             'WarehouseController::index'^);
    echo     $routes-^>get^('master/warehouses/datatables',  'WarehouseController::datatables'^);
    echo     $routes-^>get^('master/warehouses/(:num^)',     'WarehouseController::getById/$1'^);
    echo     $routes-^>post^('master/warehouses/store',      'WarehouseController::store'^);
    echo     $routes-^>post^('master/warehouses/(:num^)/delete','WarehouseController::delete/$1'^);
    echo }^);
)

echo     [OK] Warehouse

:: ════════════════════════════════════════════════════════════
::  [4/6] SHARED: Controllers, Helpers, Seeds, Error Views
:: ════════════════════════════════════════════════════════════
echo [4/6] Membuat folder Controllers, Helpers, Seeds, Errors...

mkdir "%CTRL%\Auth"     2>nul
mkdir "%CTRL%\Access"   2>nul
mkdir "%HELPERS%"       2>nul
mkdir "%SEEDS%"         2>nul
mkdir "%ERRORS%"        2>nul
mkdir "%AUTH_VIEWS%"    2>nul

echo     [OK] Controllers\Auth, Controllers\Access, Helpers, Seeds, errors views

:: ════════════════════════════════════════════════════════════
::  [5/6] SHARED VIEWS: templates (Phoenix Admin structure)
::  Sesuai struktur erp3a: app/Views/templates/
:: ════════════════════════════════════════════════════════════
echo [5/6] Membuat folder Views\templates...

mkdir "%TEMPLATES%"  2>nul

:: layout.php — placeholder, isi lengkap dari file output session sebelumnya
> "%TEMPLATES%\layout.php" (
    echo ^<?php
    echo ^/**
    echo  * layout.php — Layout utama ERP Textile
    echo  * Salin konten lengkap dari file output: layouts/templates/layout.php
    echo  * yang sudah dihasilkan pada sesi sebelumnya.
    echo  *
    echo  * Struktur: Phoenix Admin slim navbar + vertical sidebar + content + footer
    echo  * Include: templates/horizontalbar, templates/verticalbar, templates/footer
    echo  *^/
    echo ^?>
    echo ^<!-- REPLACE WITH FULL CONTENT FROM layouts/templates/layout.php --^>
)

:: verticalbar.php — placeholder
> "%TEMPLATES%\verticalbar.php" (
    echo ^<?php
    echo ^/**
    echo  * verticalbar.php — Sidebar dinamis per modul
    echo  * Salin konten lengkap dari file output: layouts/templates/verticalbar.php
    echo  *^/
    echo ^?>
    echo ^<!-- REPLACE WITH FULL CONTENT FROM layouts/templates/verticalbar.php --^>
)

:: horizontalbar.php — placeholder
> "%TEMPLATES%\horizontalbar.php" (
    echo ^<?php
    echo ^/**
    echo  * horizontalbar.php — Top navbar Phoenix Admin
    echo  * Salin konten lengkap dari file output: layouts/templates/horizontalbar.php
    echo  *^/
    echo ^?>
    echo ^<!-- REPLACE WITH FULL CONTENT FROM layouts/templates/horizontalbar.php --^>
)

:: footer.php — langsung isi karena singkat
> "%TEMPLATES%\footer.php" (
    echo ^<footer class="footer position-absolute"^>
    echo     ^<div class="row g-0 justify-content-between align-items-center h-100" style="min-width: 600px;"^>
    echo         ^<div class="col-12 col-sm-auto text-center"^>
    echo             ^<p class="mb-0 mt-2 mt-sm-0 text-body"^>
    echo                 ERP Textile Dyeing ^&amp; Finishing
    echo                 ^<span class="d-none d-sm-inline-block mx-1"^>^|^</span^>
    echo                 ^&copy; ^<?= date^('Y'^) ?^>
    echo             ^</p^>
    echo         ^</div^>
    echo         ^<div class="col-12 col-sm-auto text-center"^>
    echo             ^<p class="mb-0 text-body-tertiary text-opacity-85"^>v1.0.0^</p^>
    echo         ^</div^>
    echo     ^</div^>
    echo ^</footer^>
)

echo     [OK] app/Views/templates/ (layout, verticalbar, horizontalbar, footer^)

:: ════════════════════════════════════════════════════════════
::  [6/6] PLACEHOLDER: Controllers & Views auth/error
:: ════════════════════════════════════════════════════════════
echo [6/6] Membuat placeholder Controllers dan Views...

:: DashboardController
> "%CTRL%\DashboardController.php" (
    echo ^<?php
    echo.
    echo namespace App\Controllers;
    echo.
    echo class DashboardController extends BaseController
    echo {
    echo     public function index^(^)
    echo     {
    echo         return view^('dashboard/index', [
    echo             'title' =^> 'Dashboard',
    echo         ]^);
    echo     }
    echo }
)

:: Buat folder dan view dashboard
mkdir "%VIEWS%\dashboard" 2>nul
> "%VIEWS%\dashboard\index.php" (
    echo ^<?php $this-^>extend^('templates/layout'^); ?^>
    echo ^<?php $this-^>section^('content'^); ?^>
    echo.
    echo ^<div class="pb-5"^>
    echo     ^<div class="row g-4"^>
    echo         ^<div class="col-12"^>
    echo             ^<h2 class="mb-0"^>Dashboard^</h2^>
    echo             ^<p class="text-body-tertiary"^>Selamat datang di ERP Textile Dyeing ^&amp; Finishing^</p^>
    echo         ^</div^>
    echo     ^</div^>
    echo ^</div^>
    echo.
    echo ^<?php $this-^>endSection^(^); ?^>
)

:: LoginController placeholder
> "%CTRL%\Auth\LoginController.php" (
    echo ^<?php
    echo ^/**
    echo  * LoginController.php
    echo  * Salin konten lengkap dari file output: setup/Auth/LoginController.php
    echo  *^/
    echo namespace App\Controllers\Auth;
    echo use App\Controllers\BaseController;
    echo class LoginController extends BaseController
    echo {
    echo     public function index^(^) { return view^('auth/login'^); }
    echo     public function attempt^(^) { }
    echo     public function logout^(^) { auth^(^)-^>logout^(^); return redirect^(^)-^>to^('login'^); }
    echo }
)

:: Login view placeholder
> "%AUTH_VIEWS%\login.php" (
    echo ^<?php
    echo ^/**
    echo  * login.php — View halaman login Phoenix Admin
    echo  * Salin konten lengkap dari file output: setup/Auth/login.php
    echo  *^/
    echo ?^>
    echo ^<!-- REPLACE WITH FULL CONTENT FROM setup/Auth/login.php --^>
)

:: ErrorController
> "%CTRL%\ErrorController.php" (
    echo ^<?php
    echo ^/**
    echo  * ErrorController.php
    echo  * Salin konten lengkap dari file output: setup/ErrorController.php
    echo  *^/
    echo namespace App\Controllers;
    echo use CodeIgniter\Controller;
    echo class ErrorController extends Controller
    echo {
    echo     public function notFound^(^)   { return $this-^>response-^>setStatusCode^(404^)-^>setBody^(view^('errors/error_404'^)^); }
    echo     public function forbidden^(^)  { return $this-^>response-^>setStatusCode^(403^)-^>setBody^(view^('errors/error_403'^)^); }
    echo     public function serverError^(^){ return $this-^>response-^>setStatusCode^(500^)-^>setBody^(view^('errors/error_500'^)^); }
    echo }
)

:: Error views placeholder
> "%ERRORS%\error_404.php" (
    echo ^<?php ^/** Salin dari setup/errors/error_404.php *^/ ?^>
    echo ^<!-- REPLACE WITH FULL CONTENT FROM setup/errors/error_404.php --^>
)
> "%ERRORS%\error_403.php" (
    echo ^<?php ^/** Salin dari setup/errors/error_403.php *^/ ?^>
    echo ^<!-- REPLACE WITH FULL CONTENT FROM setup/errors/error_403.php --^>
)
> "%ERRORS%\error_500.php" (
    echo ^<?php ^/** Salin dari setup/errors/error_500.php *^/ ?^>
    echo ^<!-- REPLACE WITH FULL CONTENT FROM setup/errors/error_500.php --^>
)

:: auth_helper.php placeholder
> "%HELPERS%\auth_helper.php" (
    echo ^<?php
    echo ^/**
    echo  * auth_helper.php — Helper canDo^(^), canAny^(^), canAll^(^), currentUserName^(^)
    echo  * Salin konten lengkap dari file output: layouts/auth_helper.php
    echo  *^/
    echo if ^(! function_exists^('canDo'^)^) {
    echo     function canDo^(string $permission^): bool {
    echo         $user = auth^(^)-^>user^(^);
    echo         if ^($user === null^) return false;
    echo         if ^($user-^>inGroup^('superadmin'^)^) return true;
    echo         return $user-^>can^($permission^);
    echo     }
    echo }
)

echo     [OK] Controllers (Dashboard, Auth, Error^) + Views (dashboard, auth, errors^) + Helpers

:: ════════════════════════════════════════════════════════════
::  RINGKASAN HASIL
:: ════════════════════════════════════════════════════════════
echo.
echo ============================================================
echo  STRUKTUR BERHASIL DIBUAT
echo ============================================================
echo.
echo  app/
echo  +-- Controllers/
echo  ^|   +-- Auth/LoginController.php
echo  ^|   +-- Access/  (UserController, GroupController, dll^)
echo  ^|   +-- DashboardController.php
echo  ^|   +-- ErrorController.php
echo  +-- Helpers/
echo  ^|   +-- auth_helper.php
echo  +-- Modules/
echo  ^|   +-- HRM/
echo  ^|   ^|   +-- Config/Routes.php     [LENGKAP]
echo  ^|   ^|   +-- Controllers/
echo  ^|   ^|   +-- Database/Migrations/
echo  ^|   ^|   +-- Models/
echo  ^|   ^|   +-- Views/ (departments, positions, employees^)
echo  ^|   +-- Production/
echo  ^|   ^|   +-- Config/Routes.php     [LENGKAP]
echo  ^|   ^|   +-- Controllers/
echo  ^|   ^|   +-- Database/Migrations/
echo  ^|   ^|   +-- Models/
echo  ^|   ^|   +-- Views/ (work_orders, machines, checksheets, reports^)
echo  ^|   +-- Warehouse/
echo  ^|       +-- Config/Routes.php     [LENGKAP]
echo  ^|       +-- Controllers/
echo  ^|       +-- Database/Migrations/
echo  ^|       +-- Models/
echo  ^|       +-- Views/ (chemicals, formulations, stocks, warehouses^)
echo  +-- Views/
echo      +-- auth/login.php
echo      +-- dashboard/index.php
echo      +-- errors/ (error_404, error_403, error_500^)
echo      +-- templates/ (layout, verticalbar, horizontalbar, footer^)
echo.
echo ============================================================
echo  FILE YANG PERLU DISALIN MANUAL (isi konten lengkap^):
echo ============================================================
echo.
echo  File output sesi ini    -^>  Tujuan di proyek
echo  ─────────────────────────────────────────────────────────
echo  setup/Autoload.php      -^>  app/Config/Autoload.php
echo  setup/Routes.php        -^>  app/Config/Routes.php
echo  setup/.env              -^>  .env  (root proyek^)
echo  setup/SuperAdminSeeder  -^>  app/Database/Seeds/
echo  setup/ErrorController   -^>  app/Controllers/
echo  setup/errors/*.php      -^>  app/Views/errors/
echo  setup/Auth/Login*.php   -^>  app/Controllers/Auth/ dan app/Views/auth/
echo  layouts/auth_helper.php -^>  app/Helpers/
echo  layouts/templates/*.php -^>  app/Views/templates/
echo.
echo ============================================================
echo  LANGKAH SELANJUTNYA (jalankan di root proyek^):
echo ============================================================
echo.
echo  1. Salin semua file di atas ke posisi yang tepat
echo  2. php spark key:generate
echo  3. php spark migrate --all
echo  4. php spark db:seed SuperAdminSeeder
echo  5. php spark serve
echo  6. Login: admin@erp-textile.local / Admin@1234
echo.
echo ============================================================
echo.
pause