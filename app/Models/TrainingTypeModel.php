<?php

namespace App\Models;

use App\Entities\TrainingType;
use App\Traits\ModelRecord;
use CodeIgniter\Model;

class TrainingTypeModel extends Model
{    
    use ModelRecord;

    protected $table      = 'training_type';
    protected $primaryKey = 'id';
    protected $returnType    = TrainingType::class;
    protected $useSoftDeletes = true;
    protected $allowedFields = [
                'id',
        'name',
        'quota',
        'quota_used',
        'status',
        'is_purna_pmi',
        'is_jobseekers',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    protected $useTimestamps   = true;
    protected $skipValidation     = false;
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
        if (!empty($auth->user())) {
            $data['data']['updated_by'] = $auth->user()?->id;
        }
        return $data;
    }

    protected function setDeletdBy(array $data)
    {
        $auth = service('authentication');
        $data['data']['deleted_by'] = $auth->user()->id;
        return $data;
    }
    public function getDataTableQuery(?string $search = null, ?array $order = null, ?array $columns = null, ?int $start = null, ?int $length = null, $filter = null)
    {
        $builder = $this->builder();
        $builder->where('deleted_at', null);

        if ($filter) {
            foreach ($filter as $field => $value) {
                if ($value !== null && $value !== '') {
                    if (strpos($field, '=') !== false || strpos($field, '>') !== false || strpos($field, '<') !== false) {
                        $builder->groupStart()
                            ->where($field, $value)
                            ->groupEnd();
                    }
                    else{
                        $builder->groupStart()
                            ->like('LOWER(' . $field . ')', strtolower($value), 'both', null)
                            ->groupEnd();                        
                    }
                }
            }
        }
        if (!empty($search)) {
            $search = strtolower($search);
            $builder->groupStart()
                ->like('LOWER(name)', $search, 'both', null, true)
                ->orLike("LOWER(case when is_jobseekers = 1 then 'Job Seekers' else '' end)", $search, 'both', null, true)
                ->orLike("LOWER(case when is_purna_pmi = 1 then 'Purna PMI' else '' end)", $search, 'both', null, true)
                ->groupEnd();
        }
        if (!empty($order) && !empty($columns)) {
            foreach ($order as $ord) {
                $columnName = $columns[$ord['column']]['data'];
                if($columnName != "group"){
                    $builder->orderBy($columnName, $ord['dir']);
                }
            }
        }
        if ($length !== null && $start !== null  && $length !== -1  && $start !== -1) {
            $builder->limit($length, $start);
        }

        return $builder;
    }

    public function countFiltered(?string $search = null): int
    {
        return $this->getDataTableQuery($search)->countAllResults();
    }

    public function getData(?string $search = null, ?array $order = null, ?array $columns = null, ?int $start = null, ?int $length = null, $filter = null)
    {
        return $this->getDataTableQuery($search, $order, $columns, $start, $length, $filter)
            ->get()
            ->getCustomResultObject(\App\Entities\TrainingType::class);
    }

}