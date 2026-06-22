<?php

namespace App\Modules\Production\Controllers;

use App\Controllers\BaseController;
use App\Modules\Production\Models\FlowProcessModel;
use Hermawan\DataTables\DataTable;

class FlowProcessController extends BaseController
{
    protected FlowProcessModel $model;

    public function __construct()
    {
        $this->model = new FlowProcessModel();
    }

    // ============================================================
    // DATATABLE ENDPOINT
    // ============================================================

    public function datatables()
    {
        if (!canDo('production.flow-processes.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $designId = (int) $this->request->getGet('design_id');

        if ($designId <= 0) {
            return $this->jsonError('design_id wajib diisi', 422);
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('flow_processes')
            ->select([
                'flow_processes.id',
                'flow_processes.flow_name',
                'flow_processes.segment',   // ← tambahan
                'flow_processes.status',
                'flow_processes.created_at',
                '(SELECT COUNT(*) FROM flow_process_steps WHERE flow_process_steps.flow_process_id = flow_processes.id) as step_count',
            ])
            ->where('flow_processes.design_id', $designId)
            ->where('flow_processes.deleted_at', null);

        return DataTable::of($builder)
            ->addNumbering('no')
            ->setSearchableColumns([
                'flow_processes.flow_name',
                'flow_processes.segment',   // ← agar bisa dicari
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

        if (!canDo('production.flow-processes.view')) {
            return $this->jsonError('Akses ditolak', 403);
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

        if ($isUpdate && !canDo('production.flow-processes.edit')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        if (!$isUpdate && !canDo('production.flow-processes.create')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $rules = [
            'design_id' => 'required|is_natural_no_zero',
            'flow_name' => 'required|max_length[150]',
            'segment'   => 'required|in_list[Interior,Otomotif,Lain-Lain]',  // ← tambahan
            'status'    => 'required|in_list[Active,Draft,Archived]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ], 422);
        }

        $steps = $this->parseSteps($this->request->getPost('steps'));

        if (empty($steps)) {
            return $this->jsonResponse([
                'status' => 'error',
                'errors' => ['steps' => 'Minimal 1 step harus diisi'],
            ], 422);
        }

        $userId = auth()->id();

        $data = [
            'design_id'   => (int) $this->request->getPost('design_id'),
            'flow_name'   => trim($this->request->getPost('flow_name')),
            'segment'     => $this->request->getPost('segment'),             // ← tambahan
            'description' => trim($this->request->getPost('description') ?? '') ?: null,
            'status'      => $this->request->getPost('status'),
        ];

        if ($isUpdate) {
            $data['updated_by'] = $userId;
            $result = $this->model->updateWithSteps($id, $data, $steps);
        } else {
            $data['created_by'] = $userId;
            $result = $this->model->createWithSteps($data, $steps);
        }

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    public function delete(int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->jsonError('Method not allowed', 405);
        }

        if (!canDo('production.flow-processes.delete')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        $result = $this->model->deletePermanent($id);

        return $this->jsonResponse($result, $result['status'] === 'success' ? 200 : 422);
    }

    // ============================================================
    // AJAX — PROCESS NAMES / CHEMICAL CODES (Select2 tags)
    // ============================================================

    public function processNames()
    {
        if (!canDo('production.flow-processes.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getDistinctProcessNames(),
        ]);
    }

    public function chemicalCodes()
    {
        if (!canDo('production.flow-processes.view')) {
            return $this->jsonError('Akses ditolak', 403);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->model->getDistinctChemicalCodes(),
        ]);
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private function parseSteps(?string $raw): array
    {
        if (!$raw) {
            return [];
        }

        $decoded = json_decode($raw, true);

        if (!is_array($decoded)) {
            return [];
        }

        $steps = [];
        foreach ($decoded as $step) {
            $stepType = ($step['step_type'] ?? 'process') === 'chemical' ? 'chemical' : 'process';

            if ($stepType === 'chemical') {
                $chemicalCode = trim((string) ($step['chemical_code'] ?? ''));
                if ($chemicalCode === '') {
                    continue;
                }
                $steps[] = [
                    'step_no'       => (int) ($step['step_no'] ?? (count($steps) + 1)),
                    'step_type'     => 'chemical',
                    'chemical_code' => $chemicalCode,
                ];
            } else {
                $processName = trim((string) ($step['process_name'] ?? ''));
                if ($processName === '') {
                    continue;
                }
                $steps[] = [
                    'step_no'      => (int) ($step['step_no'] ?? (count($steps) + 1)),
                    'step_type'    => 'process',
                    'process_name' => $processName,
                ];
            }
        }

        return $steps;
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
