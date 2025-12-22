<?php

use Myth\Auth\Models\GroupModel;

if (! function_exists('get_user_permissions')) {
    function get_user_permissions(): array
    {
        $auth = service('authentication');
        $user = $auth->user();

        return $user->getPermissions();
    }
}

if (!function_exists('buildMenuTree')) {
    function buildMenuTree(array $elements, $parentId = null): array
    {
        $permissions = get_user_permissions();
        if (empty($permissions)) return [];

        $viewPermissions = array_filter($permissions, function ($perm) {
            return str_ends_with($perm, '.view');
        });
        $branch = [];

        // dd($viewPermissions);
        foreach ($elements as $element) {
            if ($element['parent_id'] === $parentId) {
                $children = buildMenuTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $hasPermission = ! empty(array_filter($viewPermissions, function ($perm) use ($element) {
                    return $element['url'] == "/" || $element['is_group'] || !isset($element['url']) ? str_contains($perm,  strtolower($element['name'])) : $perm == strtolower(str_replace('/', '.', $element['url']) . '.view');
                }));
                if ($hasPermission) {
                    $branch[] = $element;
                }
            }
        }

        return $branch;
    }
}

if (!function_exists('getMenuHierarchyNames')) {
    function getMenuHierarchyNames(array $menus, $parentId = null, $prefix = ''): array
    {
        $result = [];

        foreach ($menus as $menu) {
            if ($menu['parent_id'] === $parentId) {
                $name = $prefix ? $prefix . '/' . $menu['name'] : $menu['name'];

                $result[] = $name;
                $children = getMenuHierarchyNames($menus, $menu['id'], $name);
                if ($children) {
                    $result = array_merge($result, $children);
                }
            }
        }

        return $result;
    }
}

if (!function_exists('getMenuPathById')) {
    function getMenuPathById(array $menus, int $menuId): ?string
    {
        $indexedMenus = [];
        foreach ($menus as $menu) {
            $indexedMenus[$menu['id']] = $menu;
        }

        if (!isset($indexedMenus[$menuId])) {
            return null;
        }

        $path = [];
        $currentId = $menuId;

        while ($currentId !== null) {
            $menu = $indexedMenus[$currentId];
            array_unshift($path, $menu['name']); // masukkan di depan supaya urut dari parent
            $currentId = $menu['parent_id'];
        }

        return implode('/', $path);
    }
}
