<?php

namespace App\Models;

use App\Entities\Company;
use App\Traits\ModelRecord;
use CodeIgniter\Model;
use Myth\Auth\Password;

class CompanyModel extends BaseModel
{
    use ModelRecord;

    protected $table        = 'companies';
    protected $primaryKey   = 'id';
    protected $returnType    = Company::class;
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'id',
        'code',
        'name',
        'email',
        'npwp',
        'lat',
        'long',
        'phone',
        'address',
        'about',
        'business_sector',
        'user_id',
        'logo',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'username',
        'password_hash',
    ];

    protected $username;
    protected $password_hash;
    protected $useTimestamps   = true;
    protected $skipValidation     = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $beforeInsert = ['generateCode', 'setCreatedBy', 'transformStatus', 'validateRelationUser'];
    protected $beforeUpdate = ['setUpdatedBy', 'transformStatus', 'validateRelationUser'];
    protected $beforeDelete = ['setDeletdBy'];
    protected $sequenceField      = 'code';
    protected $sequencePrefix     = 'COMP';
    protected $sequenceDateFormat = 'Ymd';
    protected $sequenceDigits     = 3;

    protected $validationRules = [
        'name'      => 'required|min_length[3]',
        'address'      => 'required|min_length[3]',
        'about'      => 'required|min_length[3]',
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
        $data['data']['updated_by'] = $auth->user()->id;
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
                ->orLike('LOWER(code)', $search, 'both', null, true)
                ->orLike('LOWER(email)', $search, 'both', null, true)
                ->orLike('LOWER(npwp)', $search, 'both', null, true)
                ->orLike('LOWER(phone)', $search, 'both', null, true)
                ->orLike('LOWER(address)', $search, 'both', null, true)
                ->orLike('LOWER(business_sector)', $search, 'both', null, true)
                ->groupEnd();
        }
        if (!empty($order) && !empty($columns)) {
            foreach ($order as $ord) {
                $columnName = $columns[$ord['column']]['data'];
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
            ->getCustomResultObject(\App\Entities\Company::class);
    }

    protected function validateRelationUser(array $data)
    {
        $userModel = new UserModel();
        $groupModel = new GroupModel();
        $payload   = $data['data'];
        $this->username = $this->username ?? $payload['username'];
        $this->password_hash = $this->password_hash ?? $payload['password_hash'];


        $user = $userModel->where('username', $this->username ?? '')->first();
        $group = $groupModel->where('name', 'company')->first();
        if (empty($group)) {
            $this->setError('username', 'Please make role for Company');
            $data['result'] = false;
            return $data;
        }
        $payload['user_type'] = 'company';
        $payload['active'] = $payload['status'];

        if (!empty($user) && (isset($payload['user_id']) ? $user->id != $payload['user_id'] : true)) {
            // dd('exists');
            $this->setError('username', 'Username already exists');
            $data['result'] = false;
            return $data;
        }
        if (!empty($user) && $user->user_type == "admin") {
            // dd('admin');
            $this->setError('username', 'Username already exists as admin');
            $data['result'] = false;
            return $data;
        }
        if (!empty($user) && $userModel->where('email', $payload['email'])->countAllResults() > 0 && $user->id != $payload['user_id']) {
            // dd('admin');
            $this->setError('username', 'Email already exists');
            $data['result'] = false;
            return $data;
        }
        unset($data['data']['username']);
        unset($data['data']['password_hash']);
        return $data;
    }


    protected function relationUser(array $data)
    {
        $userModel = new UserModel();
        $groupModel = new GroupModel();

        $user = $userModel->where('username', $username ?? '')->first();
        $group = $groupModel->where('name', 'company')->first();

        $payload   = $data['data'];
        $payload['username'] = $this->username;
        $payload['password_hash'] = $this->password_hash;
        if (empty($user)) {
            $payload['password_hash'] = Password::hash($payload['password_hash']);
            $id = $userModel->insert($payload);
            if (! $id) {
                foreach ($userModel->errors() as $field => $error) {
                    $this->setError($field, $error);
                }
                $data['result'] = false;
                return $data;
            }
            $payload['id'] = $id;
            $userModel->addGroup($payload, $group->id);
            $data['data']['user_id'] = $id;
        } else {
            $payload['id'] = $user->id;

            if (isset($payload['password_hash'])) {
                $payload['password_hash'] = Password::hash($payload['password_hash']);
            } else {
                $payload['password_hash'] = $user->password_hash;
            }
            if (! $userModel->update((int)$user->id, $payload)) {
                foreach ($userModel->errors() as $field => $error) {
                    $this->setError($field, $error);
                }
                $data['result'] = false;
                return $data;
            }
            $userModel->clearGroup($payload);
            $userModel->addGroup($payload, $group->id);
            $data['data']['user_id'] = $user->id;
        }

        return $data;
    }
}
