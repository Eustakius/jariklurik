<?php

namespace App\Models;

use App\Entities\Setting;
use App\Traits\ModelRecord;
use CodeIgniter\Model;

class SettingModel extends Model
{
    use ModelRecord;

    protected $table      = 'settings';
    protected $primaryKey = 'id';
    protected $returnType    = Setting::class;
    protected $useTimestamps   = true;
    protected $skipValidation     = false;

    protected $allowedFields = [
        'id',
        'type',
        'name',
        'key',
        'values',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $beforeInsert = ['setCreatedBy', 'transformStatus'];
    protected $beforeUpdate = ['setUpdatedBy', 'transformStatus'];
    protected $beforeDelete = ['setDeletdBy'];

    protected function setCreatedBy(array $data)
    {
        $auth = service('authentication');
        $data['data']['created_by'] = $auth->user()->id;
        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        $auth = service('authentication');
        $data['data']['updated_by'] = $auth->user()->id;
        return $data;
    }

    protected function setDeletdBy(array $data)
    {
        $auth = service('authentication');
        $data['data']['deleted_by'] = $auth->user()->id;
        return $data;
    }


    public function getSettingSet(?int $groupId = null): int
    {
        $configSettings = config('Backend')->settings;
        $existing = $this->select('key')->findAll();
        $existingKeys = array_column($existing, 'key');

        $insertCount = 0;

        foreach ($configSettings as $setting) {
            $key = $setting['key'];
            $name = $setting['name'];
            $type = $setting['type'] ?? 'text';
            $status = $setting['status'] ?? 1;

            // Jika belum ada, insert
            if (!in_array($key, $existingKeys)) {
                $data = [
                    'type' => $type,
                    'key' => $key,
                    'name' => $name,
                    'status' => $status,
                ];

                if ($groupId !== null && $this->db->fieldExists('group_id', $this->table)) {
                    $data['group_id'] = $groupId;
                }

                $this->insert($data);
                $insertCount++;
            }
        }

        return $insertCount;
    }
}
