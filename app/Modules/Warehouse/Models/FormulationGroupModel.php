<?php

namespace App\Modules\Warehouse\Models;

use CodeIgniter\Model;

class FormulationGroupModel extends Model
{
    protected $table            = 'formulation_groups';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'group_name', 'description', 'status', 'created_by', 'updated_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cari group berdasarkan nama (case-insensitive), atau buat baru kalau belum ada.
     * Dipakai supaya user bisa "mengetik nama group baru" langsung dari form formulasi,
     * mirip tag: tidak perlu buka menu master group terlebih dahulu.
     */
    public function findOrCreateByName(string $name, ?int $userId = null): ?int
    {
        $name = trim($name);
        if ($name === '') return null;

        $existing = $this->where('LOWER(group_name)', strtolower($name))->first();
        if ($existing) return (int) $existing['id'];

        $this->insert([
            'group_name' => $name,
            'status'     => 'Active',
            'created_by' => $userId,
        ]);

        return (int) $this->getInsertID();
    }
}
