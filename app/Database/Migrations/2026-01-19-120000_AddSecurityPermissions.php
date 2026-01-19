<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSecurityPermissions extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // 1. Insert Permission
        $permissionModel = $db->table('auth_permissions');
        $permissionData = [
            'name' => 'security.view',
            'description' => 'View Security Dashboard',
        ];

        // Check if exists
        if ($permissionModel->where('name', 'security.view')->countAllResults() == 0) {
            $permissionModel->insert($permissionData);
            $permissionId = $db->insertID();
        } else {
            $row = $permissionModel->where('name', 'security.view')->get()->getRow();
            $permissionId = $row->id;
        }

        // 2. Assign to Admin Groups
        $groupModel = $db->table('auth_groups');
        // Find admin groups
        $adminGroups = $groupModel->whereIn('name', ['admin', 'administrator', 'superadmin', 'root'])->get()->getResult();

        $groupPermissionModel = $db->table('auth_groups_permissions');

        foreach ($adminGroups as $group) {
            // Check if already assigned
            $exists = $groupPermissionModel->where('group_id', $group->id)
                                         ->where('permission_id', $permissionId)
                                         ->countAllResults();
            if ($exists == 0) {
                $groupPermissionModel->insert([
                    'group_id' => $group->id,
                    'permission_id' => $permissionId
                ]);
            }
        }
    }

    public function down()
    {
        // We generally don't remove permissions in down() to avoid breaking things if other roles used it,
        // but for strictness:
        $db = \Config\Database::connect();
        $db->table('auth_permissions')->where('name', 'security.view')->delete();
    }
}
