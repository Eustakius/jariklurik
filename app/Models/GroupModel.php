<?php

namespace App\Models;

use App\Entities\Group;
use App\Traits\ModelRecord;
use CodeIgniter\Model;
use Faker\Generator;
use Myth\Auth\Entities\Permission;
use Myth\Auth\Entities\User;
use stdClass;

class GroupModel extends Model
{
    use ModelRecord;

    protected $table         = 'auth_groups';
    protected $returnType    = Group::class;
    protected $skipValidation     = false;
    protected $allowedFields = [
        'name',
        'description',
    ];
    protected $validationRules = [
        'id'          => 'permit_empty',
        'name'        => 'required|max_length[255]|is_unique[auth_groups.name,id,{id}]',
        'description' => 'max_length[255]',
    ];

    /**
     * The permission model to use.
     *
     * @see getPermissionsForGroup()
     */
    protected string $permissionModel = PermissionModel::class;

    //--------------------------------------------------------------------
    // Users
    //--------------------------------------------------------------------

    /**
     * Adds a single user to a single group.
     *
     * @return bool
     */
    public function addUserToGroup(int $userId, int $groupId)
    {
        cache()->delete("{$groupId}_users");
        cache()->delete("{$userId}_groups");
        cache()->delete("{$userId}_permissions");

        $data = [
            'user_id'  => $userId,
            'group_id' => $groupId,
        ];

        return (bool) $this->db->table('auth_groups_users')->insert($data);
    }

    /**
     * Removes a single user from a single group.
     *
     * @param int|string $groupId
     *
     * @return bool
     */
    public function removeUserFromGroup(int $userId, $groupId)
    {
        cache()->delete("{$groupId}_users");
        cache()->delete("{$userId}_groups");
        cache()->delete("{$userId}_permissions");

        return $this->db->table('auth_groups_users')
            ->where([
                'user_id'  => $userId,
                'group_id' => (int) $groupId,
            ])->delete();
    }

    /**
     * Removes a single user from all groups.
     *
     * @return bool
     */
    public function removeUserFromAllGroups(int $userId)
    {
        cache()->delete("{$userId}_groups");
        cache()->delete("{$userId}_permissions");

        return $this->db->table('auth_groups_users')
            ->where('user_id', $userId)
            ->delete();
    }

    /**
     * Returns an array of all groups that a user is a member of.
     *
     * @return array[]
     */
    public function getGroupsForUser(int $userId)
    {
        if (null === $found = cache("{$userId}_groups")) {
            $found = $this->builder()
                ->select('auth_groups_users.*, auth_groups.name, auth_groups.description')
                ->join('auth_groups_users', 'auth_groups_users.group_id = auth_groups.id', 'left')
                ->where('user_id', $userId)
                ->get()->getResultArray();

            cache()->save("{$userId}_groups", $found, 300);
        }

        return $found;
    }

    /**
     * Returns an array of all users that are members of a group.
     *
     * @return array[]
     */
    public function getUsersForGroup(int $groupId)
    {
        if (null === $found = cache("{$groupId}_users")) {
            $found = $this->builder()
                ->select('auth_groups_users.*, users.*')
                ->join('auth_groups_users', 'auth_groups_users.group_id = auth_groups.id', 'left')
                ->join('users', 'auth_groups_users.user_id = users.id', 'left')
                ->where('auth_groups.id', $groupId)
                ->get()->getResultArray();

            cache()->save("{$groupId}_users", $found, 300);
        }

        return $found;
    }

    //--------------------------------------------------------------------
    // Permissions
    //--------------------------------------------------------------------

    /**
     * Gets all permissions for a group in a way that can be
     * easily used to check against:
     *
     * @return array<int, array|Permission> An array in format permissionId => permission
     */
    public function getPermissionsForGroup(int $groupId): array
    {
        $fromGroup = model($this->permissionModel)
            ->select('auth_permissions.*')
            ->join('auth_groups_permissions', 'auth_groups_permissions.permission_id = auth_permissions.id', 'inner')
            ->where('group_id', $groupId)
            ->findAll();

        $found = [];

        foreach ($fromGroup as $permission) {
            $id = is_object($permission) ? $permission->id : $permission['id'];

            $found[$id] = $permission;
        }

        return $found;
    }

    /**
     * Add a single permission to a single group, by IDs.
     *
     * @return mixed
     */
    public function addPermissionToGroup(int $permissionId, int $groupId)
    {
        $data = [
            'permission_id' => $permissionId,
            'group_id'      => $groupId,
        ];

        return $this->db->table('auth_groups_permissions')->insert($data);
    }

    //--------------------------------------------------------------------

    /**
     * Removes a single permission from a single group.
     *
     * @return mixed
     */
    public function removePermissionFromGroup(int $permissionId, int $groupId)
    {
        return $this->db->table('auth_groups_permissions')
            ->where([
                'permission_id' => $permissionId,
                'group_id'      => $groupId,
            ])->delete();
    }

    public function removeAllPermissionFromGroup(int $groupId)
    {
        return $this->db->table('auth_groups_permissions')
            ->where([
                'group_id'      => $groupId,
            ])->delete();
    }
    //--------------------------------------------------------------------

    /**
     * Removes a single permission from all groups.
     *
     * @return mixed
     */
    public function removePermissionFromAllGroups(int $permissionId)
    {
        return $this->db->table('auth_groups_permissions')
            ->where('permission_id', $permissionId)
            ->delete();
    }

    /**
     * Faked data for Fabricator.
     *
     * @return Group|stdClass See GroupFaker
     */
    public function fake(Generator &$faker)
    {
        return new Group([
            'name'        => $faker->word,
            'description' => $faker->sentence,
        ]);
    }

    public function getPermissionsForGroupSet(?int $groupId): array
    {
        $menusWithPermissions = [];
        $permissionModel = new PermissionModel();
        $permissions = $permissionModel->findAll();

        $currentPermissions = isset($groupId) ? array_column($this->getPermissionsForGroup($groupId), 'name') : [];
        foreach (config('Backend')->menus as $menu) {
            if (!$menu['is_group']) {

                $menuItem = $menu;
                $menuItem['items'] = [];
                $onlyView = array_values(array_filter(config('Backend')->permissions, function ($perm) {
                    return $perm['name'] === 'view';
                }));
                foreach ((!$menu['url'] ? $onlyView : $menu['permissions']) as $perm) {

                    helper('menu');
                    $menuTree = getMenuPathById(config('Backend')->menus, $menu['id']);
                    $permissionKey = str_replace('/', '.', str_replace(' ', '-', strtolower($menuTree))) . '.' . $perm['name'];
                    $permission = array_filter($permissions, fn($p) => $p->name === $permissionKey);

                    $permission = !empty($permission) ? reset($permission)->id : null;
                    if (!isset($permission)) {
                        $auth = service('authorization');
                        $permission = $auth->createPermission(str_replace('/', '.', str_replace(' ', '-', strtolower($menuTree))) . '.' . $perm['name'], str_replace('/', '-', $menuTree) . ' ' . $perm['label']);
                        // dd($permission);
                    }

                    $menuItem['items'][] = [
                        'id' => $permission,
                        'name' => $perm['name'],
                        'label' => $perm['label'],
                        'checked' => in_array($permissionKey, $currentPermissions), // <== Cek di sini
                    ];
                }

                $menusWithPermissions[] = $menuItem;
            }
        }

        return $menusWithPermissions;
    }
    
    public function getDataTableQuery(?string $search = null, ?array $order = null, ?array $columns = null, ?int $start = null, ?int $length = null)
    {
        $builder = $this->builder();

        if (!empty($search)) {
            $search = strtolower($search);
            $builder->groupStart()
                ->like('LOWER(name)', $search, 'both', null, true)
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

    public function getData(?string $search = null, ?array $order = null, ?array $columns = null, ?int $start = null, ?int $length = null)
    {
        return $this->getDataTableQuery($search, $order, $columns, $start, $length)
            ->get()
            ->getCustomResultObject(\App\Entities\Group::class);
    }

}
