<?php

// Fix Permissions Script for Staging
// Upload this to public/fix_permissions.php and visit /fix_permissions.php

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
$pathsPath = FCPATH . '../app/Config/Paths.php';
chdir(__DIR__ . '/../');
require $pathsPath;
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootWeb($paths);

use Config\Database;

echo "<h1>Fixing Permissions...</h1>";

$db = Database::connect();

// 1. Insert Permission 'security.view'
$permissionModel = $db->table('auth_permissions');
$permissionData = [
    'name' => 'security.view',
    'description' => 'View Security Dashboard',
];

$permissionId = 0;
$row = $permissionModel->where('name', 'security.view')->get()->getRow();

if (!$row) {
    echo "Inserting 'security.view' permission...<br>";
    $permissionModel->insert($permissionData);
    $permissionId = $db->insertID();
    echo "Permission inserted with ID: $permissionId<br>";
} else {
    echo "'security.view' permission already exists (ID: {$row->id}).<br>";
    $permissionId = $row->id;
}

// 2. Assign to Admin Groups
$groupModel = $db->table('auth_groups');
// Find admin groups
$adminGroups = $groupModel->whereIn('name', ['admin', 'administrator', 'superadmin', 'root', 'developer'])->get()->getResult();
$groupPermissionModel = $db->table('auth_groups_permissions');

if (empty($adminGroups)) {
    echo "WARNING: No admin groups found!<br>";
}

foreach ($adminGroups as $group) {
    echo "Checking group: {$group->name} (ID: {$group->id})...<br>";
    
    // Check if already assigned
    $exists = $groupPermissionModel->where('group_id', $group->id)
                                 ->where('permission_id', $permissionId)
                                 ->countAllResults();
    if ($exists == 0) {
        $groupPermissionModel->insert([
            'group_id' => $group->id,
            'permission_id' => $permissionId
        ]);
        echo " -> Assigned 'security.view' to group '{$group->name}'.<br>";
    } else {
        echo " -> Already has permission.<br>";
    }
}

echo "<h3>Done! You can now check the sidebar.</h3>";
echo "<p>Please delete this file (fix_permissions.php) from your server after successful verification.</p>";
