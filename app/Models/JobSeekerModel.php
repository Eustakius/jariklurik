<?php

namespace App\Models;

use App\Entities\JobSeeker;
use App\Traits\ModelRecord;
use CodeIgniter\Model;

class JobSeekerModel extends Model
{
    use ModelRecord;

    protected $table      = 'job_seekers';
    protected $primaryKey = 'id';
    protected $returnType = JobSeeker::class;
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
        'education_level',
        'file_statement',
        'training_type_id',
        'status',
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

        $builder->select('job_seekers.*, training_type.name as training_type_name');
        $builder->join('training_type', 'training_type.id = job_seekers.training_type_id', 'left');

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
                ->like('LOWER(job_seekers.name)', $search, 'both', null, true)
                ->orLike('LOWER(job_seekers.code)', $search, 'both', null, true)
                ->orLike('LOWER(job_seekers.email)', $search, 'both', null, true)
                ->orLike('LOWER(job_seekers.phone)', $search, 'both', null, true)
                ->orLike("LOWER(case when job_seekers.gender = 'M' then 'Male' else 'Female' end)", $search, 'both', null, true)
                ->orLike('LOWER(job_seekers.address)', $search, 'both', null, true)
                ->orLike('LOWER(job_seekers.education_level)', $search, 'both', null, true)
                ->orLike('LOWER(training_type.name)', $search, 'both', null, true)
                ->groupEnd();
        }
        // if (!empty($searchBuilder['criteria'])) {
        //     foreach ($searchBuilder['criteria'] as $criteria) {
        //         $column = $criteria['origData'] ?? null;
        //         $condition = $criteria['condition'] ?? '=';
        //         $values = $criteria['value'] ?? [];

        //         if (!$column) continue;

        //         switch ($condition) {
        //             case 'equals':
        //             case '=':
        //                 $builder->where($column, $values[0]);
        //                 break;

        //             case 'not':
        //                 $builder->where("$column !=", $values[0]);
        //                 break;

        //             case 'contains':
        //                 $builder->like($column, $values[0]);
        //                 break;

        //             case 'starts':
        //                 $builder->like($column, $values[0], 'after');
        //                 break;

        //             case 'ends':
        //                 $builder->like($column, $values[0], 'before');
        //                 break;

        //             case 'between':
        //                 $builder->where("$column >=", $values[0]);
        //                 $builder->where("$column <=", $values[1]);
        //                 break;

        //             default:
        //                 break;
        //         }
        //     }
        // }

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

    public function countFiltered(?string $search = null): int
    {
        return $this->getDataTableQuery($search)->countAllResults();
    }

    public function getData(?string $search = null, ?array $order = null, ?array $columns = null, ?int $start = null, ?int $length = null, $filter = null)
    {
        return $this->getDataTableQuery($search, $order, $columns, $start, $length, $filter)
            ->get()
            ->getCustomResultObject(\App\Entities\JobSeeker::class);
    }
}
