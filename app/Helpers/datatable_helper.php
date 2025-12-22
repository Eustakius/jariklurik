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
            $route = base_url($permission['route']);
            $id = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

            if (str_ends_with($permission['permission'], '.detail')) {
                $html .= <<<HTML
                <a href="{$route}/{$id}" 
                    class="w-8 h-8 bg-info-50 dark:bg-info-600/10 text-info-600 dark:text-info-400 rounded-full inline-flex items-center justify-center">
                    <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                </a>
            HTML;
            } elseif (str_ends_with($permission['permission'], '.update')) {
                $html .= <<<HTML
                <a href="{$route}/{$id}/edit" 
                    class="w-8 h-8 bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400 rounded-full inline-flex items-center justify-center">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
            HTML;
            } elseif (str_ends_with($permission['permission'], '.delete')) {
                $html .= <<<HTML
                <button data-method="DELETE" data-action="Are you sure you want to delete ?" 
                    data-url="{$route}/{$id}" data-modal-target="confirm-modal{$key}" data-modal-toggle="confirm-modal{$key}" 
                    class="btn-open-modal{$key} w-8 h-8 bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 rounded-full inline-flex items-center justify-center">
                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
            HTML;
            } elseif (str_ends_with($permission['permission'], '.approve')) {
                $html .= <<<HTML
                <button data-method="PUT" data-action="Are you sure you want to approve ?" 
                    data-url="{$route}/{$id}/approve" data-modal-target="confirm-modal{$key}" data-modal-toggle="confirm-modal{$key}" 
                    class="btn-open-modal{$key} w-8 h-8 bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 rounded-full inline-flex items-center justify-center">
                    <iconify-icon icon="mingcute:check-line"></iconify-icon>
                </button>
            HTML;
            } elseif (str_ends_with($permission['permission'], '.reject')) {
                $html .= <<<HTML
                <button data-method="PUT" data-action="Are you sure you want to reject ?" 
                    data-url="{$route}/{$id}/reject" data-modal-target="confirm-modal{$key}" data-modal-toggle="confirm-modal{$key}" 
                    class="btn-open-modal{$key} w-8 h-8 bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 rounded-full inline-flex items-center justify-center">
                    <iconify-icon icon="mingcute:close-line"></iconify-icon>
                </button>
            HTML;
            } elseif (str_ends_with($permission['permission'], '.revert')) {
                $html .= <<<HTML
                <button data-method="PUT" data-action="Are you sure you want to revert ?" 
                    data-url="{$route}/{$id}/revert" data-modal-target="confirm-modal{$key}" data-modal-toggle="confirm-modal{$key}" 
                    class="btn-open-modal{$key} w-8 h-8 bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400 rounded-full inline-flex items-center justify-center">
                    <iconify-icon icon="mingcute:back-line"></iconify-icon>
                </button>
            HTML;
            }
        }

        return trim($html);
    }
}
