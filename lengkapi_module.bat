@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul

echo.
echo ══════════════════════════════════════════════════════
echo   ERP Textile — Setup Module Structure
echo ══════════════════════════════════════════════════════
echo.

set BASE=app\Modules
set CREATED=0
set SKIPPED=0

:: =============================================================
::  CREATE FOLDERS
:: =============================================================

call :mkdir "%BASE%\HRM\Config"
call :mkdir "%BASE%\HRM\Controllers"
call :mkdir "%BASE%\HRM\Models"
call :mkdir "%BASE%\HRM\Views\departments"
call :mkdir "%BASE%\HRM\Views\positions"
call :mkdir "%BASE%\HRM\Views\employees"
call :mkdir "%BASE%\HRM\Database\Migrations"

call :mkdir "%BASE%\Production\Config"
call :mkdir "%BASE%\Production\Controllers"
call :mkdir "%BASE%\Production\Models"
call :mkdir "%BASE%\Production\Views\work_orders"
call :mkdir "%BASE%\Production\Views\machines"
call :mkdir "%BASE%\Production\Views\checksheets"
call :mkdir "%BASE%\Production\Views\reports"
call :mkdir "%BASE%\Production\Database\Migrations"

call :mkdir "%BASE%\Warehouse\Config"
call :mkdir "%BASE%\Warehouse\Controllers"
call :mkdir "%BASE%\Warehouse\Models"
call :mkdir "%BASE%\Warehouse\Views\chemicals"
call :mkdir "%BASE%\Warehouse\Views\chemical_categories"
call :mkdir "%BASE%\Warehouse\Views\formulations"
call :mkdir "%BASE%\Warehouse\Views\stocks"
call :mkdir "%BASE%\Warehouse\Database\Migrations"

:: =============================================================
::  HRM — Routes
:: =============================================================
call :write_routes "%BASE%\HRM\Config\Routes.php" "HRM" "hrm" "App\Modules\HRM\Controllers"

:: =============================================================
::  HRM — Controllers
:: =============================================================
call :write_controller "%BASE%\HRM\Controllers\DepartmentController.php" "App\Modules\HRM\Controllers" "DepartmentController"
call :write_controller "%BASE%\HRM\Controllers\PositionController.php"   "App\Modules\HRM\Controllers" "PositionController"
call :write_controller "%BASE%\HRM\Controllers\EmployeeController.php"   "App\Modules\HRM\Controllers" "EmployeeController"

:: =============================================================
::  HRM — Models
:: =============================================================
call :write_model "%BASE%\HRM\Models\DepartmentModel.php" "App\Modules\HRM\Models" "DepartmentModel" "departments"
call :write_model "%BASE%\HRM\Models\PositionModel.php"   "App\Modules\HRM\Models" "PositionModel"   "positions"
call :write_model "%BASE%\HRM\Models\EmployeeModel.php"   "App\Modules\HRM\Models" "EmployeeModel"   "employees"

:: =============================================================
::  HRM — Views
:: =============================================================
call :write_view "%BASE%\HRM\Views\departments\index.php" "departments/index"
call :write_view "%BASE%\HRM\Views\departments\trash.php" "departments/trash"
call :write_view "%BASE%\HRM\Views\positions\index.php"   "positions/index"
call :write_view "%BASE%\HRM\Views\positions\trash.php"   "positions/trash"
call :write_view "%BASE%\HRM\Views\employees\index.php"   "employees/index"
call :write_view "%BASE%\HRM\Views\employees\form.php"    "employees/form"
call :write_view "%BASE%\HRM\Views\employees\trash.php"   "employees/trash"

:: =============================================================
::  Production — Routes
:: =============================================================
call :write_routes "%BASE%\Production\Config\Routes.php" "Production" "production" "App\Modules\Production\Controllers"

:: =============================================================
::  Production — Controllers
:: =============================================================
call :write_controller "%BASE%\Production\Controllers\WorkOrderController.php"  "App\Modules\Production\Controllers" "WorkOrderController"
call :write_controller "%BASE%\Production\Controllers\MachineController.php"    "App\Modules\Production\Controllers" "MachineController"
call :write_controller "%BASE%\Production\Controllers\ChecksheetController.php" "App\Modules\Production\Controllers" "ChecksheetController"
call :write_controller "%BASE%\Production\Controllers\ReportController.php"     "App\Modules\Production\Controllers" "ReportController"

:: =============================================================
::  Production — Models
:: =============================================================
call :write_model "%BASE%\Production\Models\WorkOrderModel.php"  "App\Modules\Production\Models" "WorkOrderModel"  "work_orders"
call :write_model "%BASE%\Production\Models\MachineModel.php"    "App\Modules\Production\Models" "MachineModel"    "machines"
call :write_model "%BASE%\Production\Models\ChecksheetModel.php" "App\Modules\Production\Models" "ChecksheetModel" "checksheets"

:: =============================================================
::  Production — Views
:: =============================================================
call :write_view "%BASE%\Production\Views\work_orders\index.php"  "work_orders/index"
call :write_view "%BASE%\Production\Views\work_orders\form.php"   "work_orders/form"
call :write_view "%BASE%\Production\Views\work_orders\trash.php"  "work_orders/trash"
call :write_view "%BASE%\Production\Views\machines\index.php"     "machines/index"
call :write_view "%BASE%\Production\Views\machines\trash.php"     "machines/trash"
call :write_view "%BASE%\Production\Views\checksheets\index.php"  "checksheets/index"
call :write_view "%BASE%\Production\Views\checksheets\form.php"   "checksheets/form"
call :write_view "%BASE%\Production\Views\reports\index.php"      "reports/index"

:: =============================================================
::  Warehouse — Routes
:: =============================================================
call :write_routes "%BASE%\Warehouse\Config\Routes.php" "Warehouse" "warehouse" "App\Modules\Warehouse\Controllers"

:: =============================================================
::  Warehouse — Controllers
:: =============================================================
call :write_controller "%BASE%\Warehouse\Controllers\ChemicalController.php"         "App\Modules\Warehouse\Controllers" "ChemicalController"
call :write_controller "%BASE%\Warehouse\Controllers\ChemicalCategoryController.php" "App\Modules\Warehouse\Controllers" "ChemicalCategoryController"
call :write_controller "%BASE%\Warehouse\Controllers\FormulationController.php"      "App\Modules\Warehouse\Controllers" "FormulationController"
call :write_controller "%BASE%\Warehouse\Controllers\StockController.php"            "App\Modules\Warehouse\Controllers" "StockController"

:: =============================================================
::  Warehouse — Models
:: =============================================================
call :write_model "%BASE%\Warehouse\Models\ChemicalModel.php"         "App\Modules\Warehouse\Models" "ChemicalModel"         "chemicals"
call :write_model "%BASE%\Warehouse\Models\ChemicalCategoryModel.php" "App\Modules\Warehouse\Models" "ChemicalCategoryModel" "chemical_categories"
call :write_model "%BASE%\Warehouse\Models\FormulationModel.php"      "App\Modules\Warehouse\Models" "FormulationModel"      "formulations"
call :write_model "%BASE%\Warehouse\Models\StockModel.php"            "App\Modules\Warehouse\Models" "StockModel"            "stocks"

:: =============================================================
::  Warehouse — Views
:: =============================================================
call :write_view "%BASE%\Warehouse\Views\chemicals\index.php"              "chemicals/index"
call :write_view "%BASE%\Warehouse\Views\chemicals\form.php"               "chemicals/form"
call :write_view "%BASE%\Warehouse\Views\chemicals\trash.php"              "chemicals/trash"
call :write_view "%BASE%\Warehouse\Views\chemical_categories\index.php"    "chemical_categories/index"
call :write_view "%BASE%\Warehouse\Views\chemical_categories\trash.php"    "chemical_categories/trash"
call :write_view "%BASE%\Warehouse\Views\formulations\index.php"           "formulations/index"
call :write_view "%BASE%\Warehouse\Views\formulations\form.php"            "formulations/form"
call :write_view "%BASE%\Warehouse\Views\formulations\trash.php"           "formulations/trash"
call :write_view "%BASE%\Warehouse\Views\stocks\index.php"                 "stocks/index"
call :write_view "%BASE%\Warehouse\Views\stocks\form.php"                  "stocks/form"

:: =============================================================
::  SUMMARY
:: =============================================================
echo.
echo ══════════════════════════════════════════════════════
echo   Selesai^^!  %CREATED% file dibuat,  %SKIPPED% dilewati
echo ══════════════════════════════════════════════════════
echo.
echo Langkah berikutnya:
echo   1. Salin file hasil generate Claude ke folder yang sesuai
echo   2. Pastikan Autoload.php mendaftarkan namespace modul
echo   3. Pastikan Routes.php utama me-require semua modul
echo.
pause
goto :eof

:: =============================================================
::  SUBROUTINES
:: =============================================================

:mkdir
if not exist "%~1" (
    mkdir "%~1" >nul 2>&1
    echo [DIR]  %~1
)
goto :eof

:: ── Routes stub ────────────────────────────────────────────────────────────
:write_routes
:: %1=filepath %2=ModuleName %3=prefix %4=namespace
if exist "%~1" (
    echo [SKIP] %~1
    set /a SKIPPED+=1
    goto :eof
)
(
echo ^<?php
echo.
echo // =============================================================================
echo //  %~2 Routes
echo //  Prefix  : /%~3
echo //  Filter  : shield
echo //  NS      : %~4
echo // =============================================================================
echo.
echo $routes-^>group^('%~3', ['namespace' =^> '%~4', 'filter' =^> 'shield'], function ^($routes^) {
echo     // TODO: tambahkan route di sini
echo }^);
) > "%~1"
echo [FILE] %~1
set /a CREATED+=1
goto :eof

:: ── Controller stub ────────────────────────────────────────────────────────
:write_controller
:: %1=filepath %2=namespace %3=classname
if exist "%~1" (
    echo [SKIP] %~1
    set /a SKIPPED+=1
    goto :eof
)
(
echo ^<?php
echo.
echo namespace %~2;
echo.
echo use App\Controllers\BaseController;
echo.
echo class %~3 extends BaseController
echo {
echo     public function index^(^): string
echo     {
echo         // TODO: implement
echo         return view^(''^^);
echo     }
echo }
) > "%~1"
echo [FILE] %~1
set /a CREATED+=1
goto :eof

:: ── Model stub ─────────────────────────────────────────────────────────────
:write_model
:: %1=filepath %2=namespace %3=classname %4=table
if exist "%~1" (
    echo [SKIP] %~1
    set /a SKIPPED+=1
    goto :eof
)
(
echo ^<?php
echo.
echo namespace %~2;
echo.
echo use CodeIgniter\Model;
echo.
echo class %~3 extends Model
echo {
echo     protected $table            = '%~4';
echo     protected $primaryKey       = 'id';
echo     protected $useAutoIncrement = true;
echo     protected $returnType       = 'array';
echo     protected $useSoftDeletes   = true;
echo     protected $allowedFields    = [];
echo.
echo     protected $useTimestamps = true;
echo     protected $createdField  = 'created_at';
echo     protected $updatedField  = 'updated_at';
echo     protected $deletedField  = 'deleted_at';
echo }
) > "%~1"
echo [FILE] %~1
set /a CREATED+=1
goto :eof

:: ── View stub ──────────────────────────────────────────────────────────────
:write_view
:: %1=filepath %2=viewname
if exist "%~1" (
    echo [SKIP] %~1
    set /a SKIPPED+=1
    goto :eof
)
(
echo ^<?= $this-^>extend^('templates/layout'^) ?^>
echo ^<?= $this-^>section^('content'^) ?^>
echo.
echo ^<!-- TODO: implement %~2 --^>
echo.
echo ^<?= $this-^>endSection^(^) ?^>
) > "%~1"
echo [FILE] %~1
set /a CREATED+=1
goto :eof