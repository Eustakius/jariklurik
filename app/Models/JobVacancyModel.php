<?php

namespace App\Models;

use App\Entities\JobVacancy;
use App\Traits\ModelRecord;
use CodeIgniter\Model;

class JobVacancyModel extends Model
{
    use ModelRecord;

    protected $table      = 'job_vacancy';
    protected $primaryKey = 'id';
    protected $returnType    = JobVacancy::class;
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'id',
        'code',
        'position',
        'company_id',
        'country_id',
        'unisex_quota',
        'male_quota',
        'female_quota',
        'unisex_quota_used',
        'male_quota_used',
        'female_quota_used',
        'duration',
        'duration_type',
        'selection_date',
        'description',
        'requirement',
        'email',
        'status',
        'is_pin',
        'applicant',
        'applicant_process',
        'visitor',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'required_documents',
    ];
    protected $useTimestamps   = true;
    protected $skipValidation     = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $beforeInsert = ['generateCode', 'setCreatedBy', 'transformStatus', 'adjustValue'];
    protected $beforeUpdate = ['setUpdatedBy', 'transformStatus', 'adjustValue'];
    protected $beforeDelete = ['setDeletdBy'];
    protected $sequenceField      = 'code';
    protected $sequencePrefix     = 'JOB';
    protected $sequenceDateFormat = 'Ymd';
    protected $sequenceDigits     = 4;
    protected $validationRules = [
        'country_id' => 'required|integer|validateCountry',
        'company_id' => 'required|integer|validateCompany',
        'position' => 'required|min_length[3]|max_length[255]',
        'duration'  => 'required|numeric',
        'male_quota'      => 'required|numeric|greater_than_equal_to[0]',
        'female_quota'      => 'required|numeric|greater_than_equal_to[0]',
        'unisex_quota'      => 'required|numeric|greater_than_equal_to[0]',
        'duration_type' => 'required|in_list[Bulan,Tahun]',
        'country_id'    => 'required|integer',
        'company_id'    => 'required|integer',
        'description'      => 'required|min_length[3]',
        'requirement'      => 'required|min_length[3]',
        'selection_date' => 'required|valid_date[Y-m-d]',
        // 'required_documents' => 'required|validateRequiredDocuments', // Removed to fix 500 error, handled in Controller
    ];
    protected $validationMessages = [
        'country_id' => [
            'validateCountry' => 'Selected country does not exist.',
        ],
        'company_id' => [
            'validateCompany' => 'Selected company does not exist.',
        ],
        'position' => [
            'required'   => 'Posisi is required.',
            'min_length' => 'Posisi minimal 3 character.',
        ],
        'duration' => [
            'numeric' => 'Duration must be a number.',
        ],
        'selection_date' => [
            'required'   => 'Selection date is required.',
            'valid_date' => 'Selection date format YYYY-MM-DD.',
        ],
    ];

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

    protected function adjustValue(array $data)
    {
        if (isset($data['data']['description'])) {
            fixEditorHtml($data['data']['description']);
        }
        if (isset($data['data']['requirement'])) {
            fixEditorHtml($data['data']['requirement']);
        }
        return $data;
    }
    protected function validateCountry(string $str, string $fields, array $data): bool
    {
        return model('CountryModel')->where('id', $str)->countAllResults() > 0;
    }

    protected function validateCompany(string $str, string $fields, array $data): bool
    {
        return model('CompanyModel')->where('id', $str)->countAllResults() > 0;
    }

    protected function validateRequiredDocuments($str, string $fields, array $data): bool
    {
        // Log for debugging
        log_message('debug', 'validateRequiredDocuments called with: ' . print_r($str, true));
        
        // Handle null or empty value
        if ($str === null || $str === '') {
            log_message('debug', 'required_documents is null or empty');
            return false;
        }
        
        // Handle both array and JSON string formats
        $documents = is_array($str) ? $str : (is_string($str) ? json_decode($str, true) : []);
        
        log_message('debug', 'Parsed documents: ' . print_r($documents, true));
        
        if (!is_array($documents) || empty($documents)) {
            log_message('debug', 'Documents is not array or empty');
            return false;
        }

        // Check if CV is included
        if (!in_array('cv', $documents)) {
            log_message('debug', 'CV not found in documents array');
            return false;
        }

        // Check maximum 2 documents
        if (count($documents) > 2) {
            log_message('debug', 'Too many documents selected: ' . count($documents));
            return false;
        }
        
        log_message('debug', 'Validation passed!');
        return true;
    }

    public function getDataTableQuery(?string $search = null, ?array $order = null, ?array $columns = null, ?int $start = null, ?int $length = null, $filter = null)
    {
        $auth = service('authentication');
        $builder = $this->builder();
        $builder->set('status', 0)
            ->where('selection_date <', date('Y-m-d'))
            ->update();

        $builder->select('job_vacancy.*, countries.name as country_name, companies.name as company_name');
        $builder->join('countries', 'countries.id = job_vacancy.country_id', 'left');
        $builder->join('companies', 'companies.id = job_vacancy.company_id', 'left');
        $builder->where('job_vacancy.deleted_at', null);

        if ($filter) {
            foreach ($filter as $field => $value) {
                if ($value !== null && $value !== '') {
                    if (strpos($field, '=') !== false || strpos($field, '>') !== false || strpos($field, '<') !== false) {
                        $builder->groupStart()
                            ->where($field, $value)
                            ->groupEnd();
                    }
                    elseif ($field == 'CONCAT(duration,duration_type)') {
                        $builder->groupStart()
                            ->like('LOWER(' . $field . ')', strtolower($value), 'both', false)
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
        if ($auth->user()->user_type == 'company') {
            $companyModel = new CompanyModel();

            $company = $companyModel->where('user_id', $auth->user()->id)->first();
            if (!empty($company)) {
                $builder->where('job_vacancy.company_id', $company->id);
            }
            else{
                $builder->where('job_vacancy.company_id', -99);
            }
        }

        if (!empty($search)) {
            $search = strtolower($search);
            $builder->groupStart()
                ->like('LOWER(job_vacancy.position)', $search, 'both', null)
                ->orLike('LOWER(job_vacancy.code)', $search, 'both', null)
                ->orLike('LOWER(job_vacancy.duration)', $search, 'both', null)
                ->orLike('LOWER(job_vacancy.duration_type)', $search, 'both', null)
                ->orLike('LOWER(countries.name)', $search, 'both', null)
                ->orLike('LOWER(companies.name)', $search, 'both', null)
                ->groupEnd();
        }
        if (!empty($order) && !empty($columns)) {
            foreach ($order as $ord) {
                $columnName = $columns[$ord['column']]['data'];
                if($columnName == "company"){
                    $columnName ="companies.name";
                }
                elseif($columnName == "country"){
                    $columnName ="countries.name";
                }
                $builder->orderBy($columnName, $ord['dir']);
            }
            if($order[0]['name'] == "" ){
                $builder->orderBy('job_vacancy.created_at', 'desc');
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
            ->getCustomResultObject(\App\Entities\JobVacancy::class);
    }
}
