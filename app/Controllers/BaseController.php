<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */

abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    protected $currentUser;
    protected $userEmployee;
    protected $userIdentities;

    /**
     * Scope gudang untuk user yang sedang login.
     * null  = tidak dibatasi (lihat semua gudang) — superadmin/admin/warehouse_manager
     * array = HANYA boleh akses gudang dengan id di dalam array ini (bisa kosong)
     * @var array<int>|null
     */
    protected ?array $warehouseScope = null;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['url', 'auth', 'setting', 'company']);

        $this->currentUser = auth()->getUser();
        $this->userIdentities = $this->currentUser ? $this->currentUser->getIdentities() : [];

        // Load employee data
        $this->userEmployee = null;
        if ($this->currentUser && !empty($this->currentUser->employee_id)) {
            $db = \Config\Database::connect();

            // Query dengan column yang benar
            $query = $db->table('employees')
                ->select('employees.*, departments.id as department_id, departments.department as department_name, positions.position_name')
                ->join('positions', 'positions.id = employees.position_id', 'left')
                ->join('departments', 'departments.id = positions.department_id', 'left')
                ->where('employees.id', $this->currentUser->employee_id)
                ->get();

            $this->userEmployee = $query->getRowArray();
        }

        // Hitung scope gudang (dipakai untuk auto-filter di controller Warehouse & di frontend)
        helper('warehouse');
        $this->warehouseScope = userWarehouseScope($this->currentUser, $this->userEmployee);

        // Share to all views
        $viewData = [
            'user' => $this->currentUser,
            'user_identities' => $this->userIdentities,
            'user_employee' => $this->userEmployee,
            'warehouse_scope' => $this->warehouseScope,
        ];

        \Config\Services::renderer()->setData($viewData);
    }

    /**
     * Get current user with employee data
     */
    protected function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * Get employee data of current user
     */
    protected function getUserEmployee()
    {
        return $this->userEmployee;
    }

    /**
     * Get user identities
     */
    protected function getUserIdentities()
    {
        return $this->userIdentities;
    }

    /**
     * Check if current user has employee relation
     */
    protected function hasEmployeeRelation(): bool
    {
        return !empty($this->userEmployee);
    }

    /**
     * Scope gudang user login. null = tak dibatasi, array = daftar id gudang yang boleh diakses.
     * @return array<int>|null
     */
    protected function getWarehouseScope(): ?array
    {
        return $this->warehouseScope;
    }
}
