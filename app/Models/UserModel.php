<?php

namespace App\Models;

use App\Traits\ModelRecord;
use CodeIgniter\Model;
use Faker\Generator;
use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Entities\User;

/**
 * @method User|null first()
 */
class UserModel extends Model
{
    use ModelRecord;
    
    protected $table          = 'users';
    protected $primaryKey     = 'id';
    protected $returnType     = 'App\Entities\User';
    protected $useSoftDeletes = true;
    protected $allowedFields  = [
        'email',
        'name',
        'username',
        'password_hash',
        'reset_hash',
        'reset_at',
        'reset_expires',
        'activate_hash',
        'status',
        'status_message',
        'active',
        'force_pass_reset',
        'permissions',
        'deleted_at',
        'user_type'
    ];
    protected $useTimestamps   = true;
    protected $validationRules = [
        'id'            => 'permit_empty',
        // 'email'         => 'required|valid_email|is_unique[users.email,id,{id}]',
        'username'      => 'required|alpha_numeric_punct|min_length[3]|max_length[30]|is_unique[users.username,id,{id}]',
        'password_hash' => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $afterInsert        = ['addToGroup'];
    protected $beforeInsert = ['transformActive'];
    protected $beforeUpdate = ['transformActive'];

    /**
     * The id of a group to assign.
     * Set internally by withGroup.
     *
     * @var int|null
     */
    protected $assignGroup;

    /**
     * Logs a password reset attempt for posterity sake.
     */
    public function logResetAttempt(string $email, ?string $token = null, ?string $ipAddress = null, ?string $userAgent = null)
    {
        $this->db->table('auth_reset_attempts')->insert([
            'email'      => $email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'token'      => $token,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Logs an activation attempt for posterity sake.
     */
    public function logActivationAttempt(?string $token = null, ?string $ipAddress = null, ?string $userAgent = null)
    {
        $this->db->table('auth_activation_attempts')->insert([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'token'      => $token,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Sets the group to assign any users created.
     *
     * @return $this
     */
    public function withGroup(string $groupName)
    {
        $group = $this->db->table('auth_groups')->where('name', $groupName)->get()->getFirstRow();

        $this->assignGroup = $group->id;

        return $this;
    }

    /**
     * Clears the group to assign to newly created users.
     *
     * @return $this
     */
    public function clearGroup($data)
    {
        $this->assignGroup = null;

        $groupModel = model(GroupModel::class);
        $groupModel->removeUserFromAllGroups($data['id']);
        
        return $this;
    }

    /**
     * If a default role is assigned in Config\Auth, will
     * add this user to that group. Will do nothing
     * if the group cannot be found.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    protected function addToGroup($data)
    {
        if (is_numeric($this->assignGroup)) {
            $groupModel = model(GroupModel::class);
            $groupModel->addUserToGroup($data['id'], $this->assignGroup);
        }

        return $data;
    }

    public function addGroup($data, $assignGroup)
    {
        $groupModel = model(GroupModel::class);
        $groupModel->addUserToGroup($data['id'], $assignGroup);

        return $data;
    }
    /**
     * Faked data for Fabricator.
     */
    public function fake(Generator &$faker): User
    {
        return new User([
            'email'    => $faker->email,
            'username' => $faker->userName,
            'password' => bin2hex(random_bytes(16)),
        ]);
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
                ->orLike('LOWER(username)', $search, 'both', null, true)
                ->orLike('LOWER(user_type)', $search, 'both', null, true)
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
            ->getCustomResultObject(\App\Entities\User::class);
    }
}
