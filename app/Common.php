<?php
if (!function_exists('getTitleFromUri')) {
    function getTitleFromUri(array $segmentsIndex = [1, 2]): string
    {
        $uri = service('uri');
        $segments = $uri->getSegments();

        $selectedSegments = array_filter(array_map(function ($i) use ($segments) {
            return $segments[$i - 1] ?? '';
        }, $segmentsIndex));

        $formatted = array_map(function ($raw) {
            $words = explode('-', $raw);
            $words = array_map(function ($w) {
                return ucfirst($w);
            }, $words);
            return implode(' ', $words);
        }, $selectedSegments);

        return implode(' ', $formatted);
    }
}

if (! function_exists('getMenuBasePageRoute')) {
    function getMenuBasePageRoute(array $page = []): string
    {
        $uri = service('uri');
        $segments = $uri->getSegments();

        $dynamicParams = array_filter([
            isset($page['action']) ? (string) $page['action'] : null,
            isset($page['id']) ? (string) $page['id'] : null,
        ]);

        $filteredSegments = array_filter($segments, function ($part) use ($dynamicParams) {
            return ! in_array($part, $dynamicParams, true);
        });

        return implode('.', $filteredSegments);
    }
}

if (! function_exists('getPermissionDirect')) {
    function getPermissionDirect($initial, $baseApi = '')
    { 
        $auth = service('authentication');
        $user = $auth->user();
        
        if (! $user) {
            return [];
        }

        $path = $initial
            ? str_replace('/', '.', ltrim($initial))
            : null;
        $permissions = $user->getPermissions();

        $result = [];

        foreach ($permissions as $permission) {
            $lastDot = strrpos($permission, '.');
            $baseName = $lastDot !== false ? substr($permission, 0, $lastDot) : $permission;

            if ('back-end.' . $baseName === $path) {
                $parts = explode('.', $permission);
                $result[] = [
                    'permission' => $permission,
                    'url' => '/' . str_replace('.', '/', $permission),
                    'route' => $initial,
                    'action' => end($parts),
                    'api_base' => $baseApi,
                ];
            }
        }
        return $result;
    }
}

if (! function_exists('getPermissionDirectBak')) {
    function getPermissionDirectBak($initial, $baseApi = '')
    {
        $auth = service('authentication');
        $user = $auth->user();

        if (! $user) {
            return [];
        }

        $path = $initial
            ? str_replace('/', '.', ltrim($initial))
            : null;
        $permissions = $user->getPermissions();

        $result = [];

        foreach ($permissions as $permission) {
            $lastDot = strrpos($permission, '.');
            $baseName = $lastDot !== false ? substr($permission, 0, $lastDot) : $permission;

            if ('back-end.' . $baseName === $path) {
                $parts = explode('.', $permission);
                $result[] = [
                    'permission' => $permission,
                    'url' => '/' . str_replace('.', '/', $permission),
                    'route' => $initial,
                    'action' => end($parts),
                    'api_base' => $baseApi,
                ];
            }
        }
        return $result;
    }
}

if (! function_exists('datatableColumns')) {
    function datatableColumns(array $middleColumns, array $options = []): array
    {
        $columns = [];

        if (! isset($options['no']) || $options['no'] === true) {
            $columns[] = [
                'title' => 'No',
                'data'  => null,
            ];
        }

        foreach ($middleColumns as $col) {
            $columns[] = $col;
        }

        if (! isset($options['action']) || $options['action'] === true) {
            $columns[] = [
                'title' => 'Action',
                'data'  => null,
            ];
        }

        return $columns;
    }
}

if (! function_exists('getKeyName')) {
    function getKeyName(object $object, $field): ?string
    {
        foreach (array_keys($object->toArray()) as $key) {
            if ($key === $field) {
                return $key;
            }
        }
        return null;
    }
}

if (!function_exists('route_resource')) {
    function route_resource(string $resourceName, $id = null, string $method = 'POST'): string
    {
        $method = strtoupper($method);

        if ($method === 'POST' && !$id) {
            $url = site_url($resourceName);
        } elseif (in_array($method, ['PUT', 'PATCH', 'DELETE'])) {
            if (!$id) {
                throw new \InvalidArgumentException("ID must be provided for $method method");
            }
            $url = site_url($resourceName . '/' . $id);
        } elseif ($method === 'GET') {
            $url = $id ? site_url($resourceName . '/' . $id) : site_url($resourceName);
        } else {
            $url = site_url($resourceName);
        }

        return $url;
    }
}

if (!function_exists('upload_file')) {
    function upload_file(string $inputName, string $folderPath, ?string $customName = null, int $maxSize = 2097152)
    {
        $file = \Config\Services::request()->getFile($inputName);

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return false;
        }
        if ($file->getSize() > $maxSize) {
            return false;
        }
        $destination = FCPATH . $folderPath;
        
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }
        $ext = $file->getClientExtension();
        if (!$customName) {
            $customName = 'file-' . time();
        }
        else{
            $customName = $customName .'-' . time();
        }
        $customName = slugify(strtolower(str_replace(' ', '-', $customName))) . '.' . $ext;
        $file->move($destination, $customName, true);
        return '/' . trim($folderPath, '/') . '/' . $customName;
    }
}

if (!function_exists('upload_file_confidential')) {
    function upload_file_confidential(string $inputName, string $folderPath, ?string $customName = null, int $maxSize = 1048576)
    {
        $file = \Config\Services::request()->getFile($inputName);

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return ['success' => false, 'error' => 'File tidak valid'];
        }

        if ($file->getSize() > $maxSize) {
            return ['success' => false, 'error' => 'Ukuran file maksimal 1 MB'];
        }

        $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $allowedMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png'
        ];

        $ext = strtolower($file->getClientExtension());
        $mime = $file->getMimeType();

        if (!in_array($ext, $allowedExtensions) || !in_array($mime, $allowedMimeTypes)) {
            return ['success' => false, 'error' => 'Tipe file tidak diizinkan'];
        }

        $destination = ROOTPATH . $folderPath;
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        if (!$customName) {
            $customName = 'file-' . time();
        }
        else{
            $customName = $customName .'-' . time();
        }

        $customName = slugify(strtolower(str_replace(' ', '-', $customName))) . '.' . $ext;
        $file->move($destination, $customName, true);

        return [
            'success' => true,
            'path' => '/' . trim($folderPath, '/') . '/' . $customName
        ];
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text, string $divider = '-'): string
    {
        $text = strtolower($text);
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = preg_replace('~' . preg_quote($divider, '~') . '+~', $divider, $text);
        $text = trim($text, $divider);
        return $text ?: 'n-a';
    }
}

if (!function_exists('shortEncrypt')) {
    function shortEncrypt($id, $key = 'rardianto')
    {
        $id = (string) $id;
        $out = '';
        for ($i = 0; $i < strlen($id); $i++) {
            $out .= chr(ord($id[$i]) ^ ord($key[$i % strlen($key)]));
        }
        return base32url_encode($out);
    }
}

if (!function_exists('shortDecrypt')) {
    function shortDecrypt($hash, $key = 'rardianto')
    {
        $hash = strtolower($hash);
        $data = base32url_decode($hash);
        $out = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $out .= chr(ord($data[$i]) ^ ord($key[$i % strlen($key)]));
        }
        return $out;
    }
}

if (!function_exists('base32url_encode')) {
    function base32url_encode($data)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyz234567';
        $binary = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $binary .= str_pad(decbin(ord($data[$i])), 8, '0', STR_PAD_LEFT);
        }
        $chunks = str_split($binary, 5);
        $out = '';
        foreach ($chunks as $chunk) {
            if (strlen($chunk) < 5) {
                $chunk = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
            }
            $out .= $alphabet[bindec($chunk)];
        }
        return rtrim($out, '=');
    }
}

if (!function_exists('base32url_decode')) {
    function base32url_decode($data)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyz234567';
        $binary = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $pos = strpos($alphabet, $data[$i]);
            if ($pos === false) continue; 
            $binary .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
        }
        $bytes = str_split($binary, 8);
        $out = '';
        foreach ($bytes as $byte) {
            if (strlen($byte) === 8) {
                $out .= chr(bindec($byte));
            }
        }
        return $out;
    }
}

if (!function_exists('convertTextToUlLi')) {
    function convertTextToUlLi(string $text): string
    {
        $text = trim($text);
        if ($text === '') {
            return '';
        }

        $text = preg_replace("/[\r\n]+/", ". ", $text);

        $sentences = preg_split('/\.\s*/', $text, -1, PREG_SPLIT_NO_EMPTY);

        $listItems = array_map(function ($item) {
            $item = trim($item);
            return $item !== '' ? "<li>{$item}.</li>" : '';
        }, $sentences);

        return '<ul>' . implode('', $listItems) . '</ul>';
    }
}


if (!function_exists('pathBack')) {
    function pathBack($request, $action = ""): string
    {
        $pathname = '/' . ltrim($request->getUri()->getPath(), '/');
        $method = strtoupper($request->getMethod());
        if($method === "POST"){
            return str_replace("/submit","", str_replace("/import","", $pathname)).( $action == "create" ? "/new" : "");
        }        
        else if($method === "PUT"){
            return $pathname."/edit";
        }       
        else if($method === "DELETE"){
            $parts = explode('/', rtrim($pathname, '/'));
            array_pop($parts);

            $urlWithoutLast = implode('/', $parts);

            return $urlWithoutLast;           
        }
        else{
            return "back-end/";
        }
    }
}

if (!function_exists('fixEditorHtml')) {
    function fixEditorHtml($content) : string
    {
        $content = preg_replace('/font-family\s*:\s*[^;"]+;?/i', '', $content);

        return $content;
    }
}