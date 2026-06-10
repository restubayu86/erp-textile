<?php

namespace App\Modules\HRM\Controllers;

use App\Controllers\BaseController;
use App\Modules\HRM\Models\EmployeeModel;
use App\Modules\HRM\Models\PositionModel;
use App\Modules\HRM\Models\DepartmentModel;
use Hermawan\DataTables\DataTable;
use CodeIgniter\HTTP\ResponseInterface;

class EmployeeController extends BaseController
{
    protected EmployeeModel    $model;
    protected PositionModel    $positionModel;
    protected DepartmentModel  $departmentModel;

    public function __construct()
    {
        $this->model           = new EmployeeModel();
        $this->positionModel   = new PositionModel();
        $this->departmentModel = new DepartmentModel();
    }

    // ============================================================
    // PAGES
    // ============================================================

    public function index(): ResponseInterface|string
    {
        if (!canDo('hrm.employees.view')) {
            return $this->forbidden();
        }

        return view('App\Modules\HRM\Views\employees\index', [
            'title'            => 'Karyawan',
            'page_title'       => 'Daftar Karyawan',
            'page_description' => 'Kelola data karyawan perusahaan',
            'breadcrumbs'      => $this->breadcrumbs(),
            'departments'      => $this->departmentModel->getAllActive(),
            'positions'        => $this->positionModel->getAllActive(),
            'work_areas'       => $this->model->getDistinctWorkAreas(),
            'shifts'           => ['NS', 'A', 'B', 'C', 'D', 'E'],
        ]);
    }

    public function trash(): ResponseInterface|string
    {
        if (!canDo('hrm.employees.delete')) {
            return $this->forbidden();
        }

        return view('App\Modules\HRM\Views\employees\trash', [
            'title'            => 'Sampah — Karyawan',
            'page_title'       => 'Sampah Karyawan',
            'page_description' => 'Karyawan yang telah dihapus',
            'breadcrumbs'      => $this->breadcrumbs([
                ['name' => 'Sampah', 'active' => true],
            ]),
        ]);
    }

    // ============================================================
    // DATATABLE ENDPOINTS
    // ============================================================

    public function datatables()
    {
        if (!canDo('hrm.employees.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('employees')
            ->select([
                'employees.id',
                'employees.nik',
                'employees.fullname',
                'employees.nickname',
                'employees.gender',
                'employees.phone',
                'employees.photo',
                'employees.work_area',
                'employees.shift',
                'employees.employment_status',
                'employees.join_date',
                'employees.status',
                'employees.created_at',
                'employees.updated_at',
                'employees.position_id',
                'positions.position_name',
                'positions.position_code',
                'departments.id as department_id',
                'departments.department as department_name',
                'cu.username as created_by_name',
                'uu.username as updated_by_name',
            ])
            ->join('positions', 'positions.id = employees.position_id', 'left')
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->join('users cu', 'cu.id = employees.created_by', 'left')
            ->join('users uu', 'uu.id = employees.updated_by', 'left')
            ->where('employees.deleted_at', null);

        // Filters
        if ($name = trim($this->request->getGet('filter_name') ?? '')) {
            $builder->groupStart()
                ->like('employees.fullname', $name)
                ->orLike('employees.nik', $name)
                ->orLike('employees.nickname', $name)
                ->groupEnd();
        }

        if ($department = trim($this->request->getGet('filter_department') ?? '')) {
            $builder->where('departments.id', $department);
        }

        if ($position = trim($this->request->getGet('filter_position') ?? '')) {
            $builder->where('employees.position_id', $position);
        }

        if ($shift = trim($this->request->getGet('filter_shift') ?? '')) {
            $builder->where('employees.shift', $shift);
        }

        if ($workArea = trim($this->request->getGet('filter_work_area') ?? '')) {
            $builder->where('employees.work_area', $workArea);
        }

        if ($empStatus = trim($this->request->getGet('filter_employment_status') ?? '')) {
            $builder->where('employees.employment_status', $empStatus);
        }

        if ($status = trim($this->request->getGet('filter_status') ?? '')) {
            $builder->where('employees.status', $status);
        }

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'employees.fullname',
                'employees.nik',
                'employees.nickname',
                'employees.phone',
                'positions.position_name',
            ])
            ->toJson(true);
    }

    public function trashDatatables()
    {
        if (!canDo('hrm.employees.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('employees')
            ->select([
                'employees.id',
                'employees.nik',
                'employees.fullname',
                'employees.nickname',
                'employees.gender',
                'employees.shift',
                'employees.employment_status',
                'employees.deleted_at',
                'positions.position_name',
                'departments.department as department_name',
                'cu.username as created_by_name',
                'du.username as deleted_by_name',
            ])
            ->join('positions', 'positions.id = employees.position_id', 'left')
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->join('users cu', 'cu.id = employees.created_by', 'left')
            ->join('users du', 'du.id = employees.deleted_by', 'left')
            ->where('employees.deleted_at IS NOT NULL');

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'employees.fullname',
                'employees.nik',
            ])
            ->toJson(true);
    }

    // ============================================================
    // AJAX — CRUD
    // ============================================================

    public function get(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        $result = $this->model->getData($id);

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 404);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        $id       = (int) $this->request->getPost('id');
        $isUpdate = $id > 0;

        if ($isUpdate && !canDo('hrm.employees.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        if (!$isUpdate && !canDo('hrm.employees.create')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $rules = [
            'nik'               => "required|max_length[20]|alpha_numeric",
            'fullname'          => 'required|max_length[100]',
            'nickname'          => 'permit_empty|max_length[50]',
            'gender'            => 'required|in_list[L,P]',
            'phone'             => 'permit_empty|max_length[20]',
            'position_id'       => 'required|is_natural_no_zero',
            'work_area'         => 'permit_empty|max_length[100]',
            'shift'             => 'required|in_list[NS,A,B,C,D,E]',
            'employment_status' => 'required|in_list[tetap,kontrak,magang]',
            'join_date'         => 'permit_empty|valid_date[Y-m-d]',
            'status'            => 'required|in_list[active,inactive]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ], 422);
        }

        $userId = auth()->id();

        $data = [
            'nik'               => strtoupper(trim($this->request->getPost('nik'))),
            'fullname'          => trim($this->request->getPost('fullname')),
            'nickname'          => trim($this->request->getPost('nickname') ?? '') ?: null,
            'gender'            => $this->request->getPost('gender'),
            'phone'             => trim($this->request->getPost('phone') ?? '') ?: null,
            'position_id'       => (int) $this->request->getPost('position_id'),
            'work_area'         => trim($this->request->getPost('work_area') ?? '') ?: null,
            'shift'             => $this->request->getPost('shift'),
            'employment_status' => $this->request->getPost('employment_status'),
            'join_date'         => $this->request->getPost('join_date') ?: null,
            'status'            => $this->request->getPost('status'),
        ];

        if ($isUpdate) {
            $data['updated_by'] = $userId;
            $result = $this->model->updateData($id, $data);
        } else {
            $data['created_by'] = $userId;
            $result = $this->model->createData($data);
        }

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function delete(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('hrm.employees.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $result = $this->model->deleteData($id, auth()->id());

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function restore(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('hrm.employees.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $result = $this->model->restoreData($id);

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function forceDelete(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('hrm.employees.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $result = $this->model->forceDeleteData($id);

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function emptyTrash()
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('hrm.employees.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $trashed = $this->model->onlyDeleted()->findAll();

        if (empty($trashed)) {
            return $this->jsonResponse(['status' => 'success', 'message' => 'Sampah sudah kosong']);
        }

        $deleted = 0;
        $skipped = 0;

        foreach ($trashed as $row) {
            $result = $this->model->forceDeleteData($row['id']);
            $result['status'] === 'success' ? $deleted++ : $skipped++;
        }

        $msg = "{$deleted} karyawan berhasil dihapus permanen";
        if ($skipped) $msg .= ", {$skipped} gagal dihapus";

        return $this->jsonResponse(['status' => 'success', 'message' => $msg]);
    }

    // ============================================================
    // AJAX — PHOTO UPLOAD
    // ============================================================

    public function uploadPhoto(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('hrm.employees.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $result = $this->model->getData($id);
        if ($result['status'] !== 'success') {
            return $this->jsonError('Karyawan tidak ditemukan', 404);
        }

        $file = $this->request->getFile('photo');

        if (!$file || !$file->isValid()) {
            return $this->jsonError('File tidak valid', 422);
        }

        $rules = ['photo' => 'uploaded[photo]|is_image[photo]|mime_in[photo,image/jpeg,image/png]|max_size[photo,2048]'];

        if (!$this->validate($rules)) {
            return $this->jsonResponse([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ], 422);
        }

        $uploadPath = FCPATH . 'uploads/employees/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Hapus foto lama
        $employee = $result['data'];
        if (!empty($employee['photo'])) {
            $oldPath = $uploadPath . $employee['photo'];
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $newName = 'EMP_' . strtoupper($employee['nik']) . '_' . time() . '.' . $file->getExtension();
        $file->move($uploadPath, $newName);

        $updateResult = $this->model->updateData($id, [
            'photo'      => $newName,
            'updated_by' => auth()->id(),
        ]);

        if ($updateResult['status'] !== 'success') {
            return $this->jsonError('Gagal menyimpan foto', 500);
        }

        return $this->jsonResponse([
            'status'  => 'success',
            'message' => 'Foto berhasil diperbarui',
            'data'    => ['photo' => $newName, 'photo_url' => base_url('uploads/employees/' . $newName)],
        ]);
    }

    public function deletePhoto(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('hrm.employees.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $result = $this->model->getData($id);
        if ($result['status'] !== 'success') {
            return $this->jsonError('Karyawan tidak ditemukan', 404);
        }

        $employee = $result['data'];
        if (!empty($employee['photo'])) {
            $photoPath = FCPATH . 'uploads/employees/' . $employee['photo'];
            if (file_exists($photoPath)) {
                @unlink($photoPath);
            }
        }

        $this->model->updateData($id, ['photo' => null, 'updated_by' => auth()->id()]);

        return $this->jsonResponse(['status' => 'success', 'message' => 'Foto berhasil dihapus']);
    }

    // ============================================================
    // AJAX — STATS & SELECT2
    // ============================================================

    public function stats()
    {
        if (!canDo('hrm.employees.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getStats(),
        ]);
    }

    public function select2()
    {
        $search      = trim($this->request->getGet('search') ?? '');
        $positionId  = $this->request->getGet('position_id');
        $departmentId = $this->request->getGet('department_id');
        $shift       = $this->request->getGet('shift');
        $workArea    = $this->request->getGet('work_area');

        $builder = $this->model->db->table('employees')
            ->select([
                'employees.id',
                'employees.nik',
                'employees.fullname',
                'employees.nickname',
                'employees.shift',
                'employees.work_area',
                'positions.position_name',
                'departments.department as department_name',
            ])
            ->join('positions', 'positions.id = employees.position_id', 'left')
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->where('employees.status', 'active')
            ->where('employees.deleted_at', null)
            ->orderBy('employees.fullname', 'ASC');

        if ($positionId) {
            $builder->where('employees.position_id', $positionId);
        }

        if ($departmentId) {
            $builder->where('positions.department_id', $departmentId);
        }

        if ($shift) {
            $builder->where('employees.shift', $shift);
        }

        if ($workArea) {
            $builder->where('employees.work_area', $workArea);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->like('employees.fullname', $search)
                ->orLike('employees.nik', $search)
                ->orLike('employees.nickname', $search)
                ->groupEnd();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $builder->limit(50)->get()->getResultArray(),
        ]);
    }

    public function checkUnique()
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        $field = $this->request->getPost('field');
        $value = trim($this->request->getPost('value') ?? '');
        $id    = (int) $this->request->getPost('id');

        if (!in_array($field, ['nik'])) {
            return $this->jsonError('Field tidak valid', 422);
        }

        $q = $this->model->where("UPPER({$field})", strtoupper($value))->where('deleted_at', null);
        if ($id > 0) $q->where('id !=', $id);

        return $this->jsonResponse([
            'status'    => 'success',
            'available' => !$q->first(),
        ]);
    }

    // ============================================================
    // AJAX — LOOKUPS
    // ============================================================

    /**
     * Ambil karyawan berdasarkan position_id.
     * Berguna untuk dropdown operator di Work Order / Checksheet.
     */
    public function getByPosition(int $positionId)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getByPosition($positionId),
        ]);
    }

    /**
     * Ambil karyawan berdasarkan department_id.
     * Berguna untuk filter laporan per departemen.
     */
    public function getByDepartment(int $departmentId)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getByDepartment($departmentId),
        ]);
    }

    /**
     * Ambil karyawan berdasarkan shift.
     * Berguna untuk absensi, penjadwalan, atau filter Work Order.
     */
    public function getByShift(string $shift)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        $validShifts = ['NS', 'A', 'B', 'C', 'D', 'E'];
        if (!in_array(strtoupper($shift), $validShifts)) {
            return $this->jsonError('Shift tidak valid', 422);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getByShift(strtoupper($shift)),
        ]);
    }

    /**
     * Ambil karyawan berdasarkan work_area.
     * Berguna untuk filter mesin/area produksi tertentu.
     */
    public function getByWorkArea()
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        $workArea = trim($this->request->getGet('work_area') ?? '');

        if ($workArea === '') {
            return $this->jsonError('Work area tidak boleh kosong', 422);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getByWorkArea($workArea),
        ]);
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private function breadcrumbs(array $extra = []): array
    {
        $base = [
            ['name' => 'Dashboard', 'url' => site_url('dashboard')],
            ['name' => 'HRM',       'url' => site_url('hrm')],
            ['name' => 'Karyawan',  'url' => site_url('hrm/employees')],
        ];

        if (empty($extra)) {
            $base[2]['active'] = true;
            unset($base[2]['url']);
            return $base;
        }

        return array_merge($base, $extra);
    }

    private function forbidden()
    {
        return redirect()->to(site_url('errors/403'));
    }

    private function jsonResponse(array $result, int $code = 200)
    {
        return $this->response
            ->setStatusCode($code)
            ->setJSON(array_merge($result, ['csrfHash' => csrf_hash()]));
    }

    private function jsonError(string $message, int $code = 500)
    {
        return $this->response
            ->setStatusCode($code)
            ->setJSON(['status' => 'error', 'message' => $message, 'csrfHash' => csrf_hash()]);
    }
}
