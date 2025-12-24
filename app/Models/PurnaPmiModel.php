<?php

namespace App\Models;

use App\Traits\ModelRecord;
use CodeIgniter\Model;

class PurnaPmiModel extends Model
{
    use ModelRecord;

    protected $table      = 'purna_pmi';
    protected $primaryKey = 'id';
    protected $useTimestamps   = true;
    protected $skipValidation     = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $beforeUpdate = ['setLog'];
    protected $beforeInsert = ['generateCode'];
    protected $sequenceField      = 'code';
    protected $sequencePrefix     = 'JS';
    protected $sequenceDateFormat = 'Ymd';
    protected $sequenceDigits     = 5;
    protected $allowedFields = [
        'id',
        'code',
        'name',
        'email',
        'phone',
        'gender',
        'birth_of_day',
        'address',
        'end_year',
        'education_level',
        'file',
        'status',
        'training_type_id',
        'created_at',
        'updated_at',
        'approved_at',
        'rejected_at',
        'reverted_at',
        'approved_by',
        'rejected_by',
        'reverted_by',
    ];

    protected function setLog(array $data)
    {
        $auth = service('authentication');

        if ($data['data']['status'] == 1) {
            $data['data']['approved_by'] = $auth->user()->id;
            $data['data']['approved_at'] = $data['data']['updated_at'];
        } else if ($data['data']['status'] == -1) {
            $data['data']['rejected_by'] = $auth->user()->id;
            $data['data']['rejected_at'] = $data['data']['updated_at'];
        } else if ($data['data']['status'] == 0) {
            $data['data']['reverted_by'] = $auth->user()->id;
            $data['data']['reverted_at'] = $data['data']['updated_at'];
        }
        return $data;
    }
    public function getDataTableQuery(?string $search = null, ?array $order = null, ?array $columns = null, ?int $start = null, ?int $length = null, $filter = null)
    {
        $builder = $this->builder();

        $builder->select('purna_pmi.*, training_type.name as training_type_name');
        $builder->join('training_type', 'training_type.id = purna_pmi.training_type_id', 'left');

        if ($filter) {
            foreach ($filter as $field => $value) {
                if ($value !== null && $value !== '') {
                    if (strpos($field, '=') !== false || strpos($field, '>') !== false || strpos($field, '<') !== false) {
                        $builder->groupStart()
                            ->where($field, $value)
                            ->groupEnd();
                    } else {
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
                ->like('LOWER(purna_pmi.name)', $search, 'both', null, true)
                ->orLike('LOWER(purna_pmi.code)', $search, 'both', null, true)
                ->orLike('LOWER(purna_pmi.email)', $search, 'both', null, true)
                ->orLike('LOWER(purna_pmi.end_year)', $search, 'both', null, true)
                ->orLike('LOWER(purna_pmi.phone)', $search, 'both', null, true)
                ->orLike("LOWER(case when purna_pmi.gender = 'M' then 'Male' else 'Female' end)", $search, 'both', null, true)
                ->orLike('LOWER(purna_pmi.address)', $search, 'both', null, true)
                ->orLike('LOWER(purna_pmi.education_level)', $search, 'both', null, true)
                ->orLike('LOWER(training_type.name)', $search, 'both', null, true)
                ->groupEnd();
        }

        if (!empty($order) && !empty($columns)) {
            foreach ($order as $ord) {
                $columnName = $columns[$ord['column']]['data'];
                if($columnName == "educationlevel"){
                    $columnName ="education_level";
                }
                elseif($columnName == "training_type"){
                    $columnName ="training_type.name";
                }
                elseif($columnName == "bod"){
                    $columnName ="birth_of_day";
                }
                $builder->orderBy($columnName, $ord['dir']);
            }
        }
        if ($length !== null && $start !== null  && $length !== -1  && $start !== -1) {
            $builder->limit($length, $start);
        }

        return $builder;
    }

    public function countFiltered(?string $search = null, $filter = null): int
    {
        return $this->getDataTableQuery($search, null, null, null, null, $filter)->countAllResults();
    }

    public function getData(?string $search = null, ?array $order = null, ?array $columns = null, ?int $start = null, ?int $length = null, $filter = null)
    {
        return $this->getDataTableQuery($search, $order, $columns, $start, $length, $filter)
            ->get()
            ->getCustomResultObject(\App\Entities\PurnaPmi::class);
    }
}
