<?php

use Myth\Auth\Models\GroupModel;

if (! function_exists('statusRender')) {
    function statusRender($status): string
    {
        $colorClass = $status == 1
            ? "bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400"
            : ($status == 9
                ? "bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400"
                : "bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400");

        $text = $status == 1 ? "Active" : ($status == 9 ? "Pending" : "Inactive");

        return "<span class=\"$colorClass px-6 py-1.5 rounded-full font-medium text-sm\">$text</span>";
    }
}

if (!function_exists('checkboxRenderFlag')) {
    function checkboxRenderFlag($value, $id, $label, $url): string
    {
        $hash = csrf_hash();
        $isChecked    = ($value == "1" || $value === 1 || $value === true ? "checked" : "");
        return <<<HTML
        <label class="inline-flex items-center cursor-pointer mt-2" for="checkbox-{$id}">
            <input type="checkbox"
                class="sr-only peer triger-update"
                id="checkbox-{$id}"
                data-id="{$id}"
                data-url="{$url}"
                name="checkbox-{$id}"
                {$isChecked}
                value="{$value}">
            
            <span class="relative w-11 h-6 bg-gray-400 peer-focus:outline-none rounded-full peer dark:bg-gray-500 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></span>          
            <span class="line-height-1 font-medium ms-3 peer-checked:text-primary-600 text-sm text-gray-600 dark:text-gray-300">
                {$label}
            </span>
        </label>
        HTML;
        // return <<<HTML
        //     <input type="checkbox"
        //         id="checkbox-{$id}"
        //         name="checkbox-{$id}"
        //         value="{$status}">
        // HTML;
    }
}


if (! function_exists('imgRender')) {
    function imgRender($data): string
    {
        if (empty($data)) {
            return '<span class="text-gray-400 italic">No Image</span>';
        }

        $src = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        return "<img src=\"$src\" alt=\"Logo\" 
        onerror=\"this.onerror=null; this.src='/assets/images/default-logo.png';\" 
        style=\"height:60px; width:auto; object-fit:contain;\">";
    }
}

if (! function_exists('fileRender')) {
    function fileRender($data): string
    {
        if (empty($data)) {
            return '<span class="text-gray-400 italic">No File</span>';
        }

        $src = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        return "
        <a href=\"{$src}\" target=\"_blank\"  
            class=\"btn bg-info-100 text-info-600 hover:bg-info-700 hover:text-white rounded-lg px-3.5 py-2 text-sm\">
            <div class=\"flex items-center justify-center gap-2\">
                <iconify-icon icon=\"ant-design:download-outlined\" class=\"text-lg\"></iconify-icon>
                Download
            </div>
        </a>
    ";
    }
}


if (! function_exists('actionButtonsRender')) {
    function actionButtonsRender(int $data, array $props, $key): string
    {
        if (empty($props)) {
            return '';
        }
        $filtereds = array_filter(
            $props,
            fn($p) => !str_ends_with($p['permission'], '.create') &&
                !str_ends_with($p['permission'], '.view')
        );

        $html = '';

        foreach ($filtereds as $permission) {
            $routeKey = $permission['route'];
            if (!str_starts_with($routeKey, 'back-end')) {
                $routeKey = 'back-end/' . ltrim($routeKey, '/');
            }
            $route = rtrim(base_url($routeKey), '/');
            $id = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

            if (str_ends_with($permission['permission'], '.detail')) {
                $html .= <<<HTML
                <a href="{$route}/{$id}" 
                    class="w-9 h-9 bg-cyan-50 hover:bg-cyan-100 text-cyan-600 dark:bg-cyan-900/20 dark:hover:bg-cyan-900/30 dark:text-cyan-400 rounded-lg inline-flex items-center justify-center transition-all duration-200">
                    <iconify-icon icon="solar:eye-broken" class="text-lg"></iconify-icon>
                </a>
            HTML;
            } elseif (str_ends_with($permission['permission'], '.update')) {
                $html .= <<<HTML
                <a href="{$route}/{$id}/edit" 
                    class="w-9 h-9 bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:hover:bg-amber-900/30 dark:text-amber-400 rounded-lg inline-flex items-center justify-center transition-all duration-200">
                    <iconify-icon icon="solar:pen-new-square-broken" class="text-lg"></iconify-icon>
                </a>
            HTML;
            } elseif (str_ends_with($permission['permission'], '.delete')) {
                $html .= <<<HTML
                <button data-method="DELETE" data-action="Are you sure you want to delete ?" 
                    data-url="{$route}/{$id}" data-modal-target="confirm-modal{$key}" data-modal-toggle="confirm-modal{$key}" 
                    class="btn-open-modal{$key} w-9 h-9 bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-900/20 dark:hover:bg-rose-900/30 dark:text-rose-400 rounded-lg inline-flex items-center justify-center transition-all duration-200">
                    <iconify-icon icon="solar:trash-bin-trash-broken" class="text-lg"></iconify-icon>
                </button>
            HTML;
            } elseif (str_ends_with($permission['permission'], '.approve')) {
                $html .= <<<HTML
                <button data-method="PUT" data-action="Are you sure you want to approve ?" 
                    data-url="{$route}/{$id}/approve" data-modal-target="confirm-modal{$key}" data-modal-toggle="confirm-modal{$key}" 
                    class="btn-open-modal{$key} w-9 h-9 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/30 dark:text-emerald-400 rounded-lg inline-flex items-center justify-center transition-all duration-200">
                    <iconify-icon icon="solar:check-circle-broken" class="text-lg"></iconify-icon>
                </button>
            HTML;
            } elseif (str_ends_with($permission['permission'], '.reject')) {
                $html .= <<<HTML
                <button data-method="PUT" data-action="Are you sure you want to reject ?" 
                    data-url="{$route}/{$id}/reject" data-modal-target="confirm-modal{$key}" data-modal-toggle="confirm-modal{$key}" 
                    class="btn-open-modal{$key} w-9 h-9 bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-900/20 dark:hover:bg-rose-900/30 dark:text-rose-400 rounded-lg inline-flex items-center justify-center transition-all duration-200">
                    <iconify-icon icon="solar:close-circle-broken" class="text-lg"></iconify-icon>
                </button>
            HTML;
            } elseif (str_ends_with($permission['permission'], '.revert')) {
                $html .= <<<HTML
                <button data-method="PUT" data-action="Are you sure you want to revert ?" 
                    data-url="{$route}/{$id}/revert" data-modal-target="confirm-modal{$key}" data-modal-toggle="confirm-modal{$key}" 
                    class="btn-open-modal{$key} w-9 h-9 bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:hover:bg-amber-900/30 dark:text-amber-400 rounded-lg inline-flex items-center justify-center transition-all duration-200">
                    <iconify-icon icon="solar:restart-broken" class="text-lg"></iconify-icon>
                </button>
            HTML;
            }
        }

        return trim($html);
    }
}
