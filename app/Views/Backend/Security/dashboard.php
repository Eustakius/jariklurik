<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Command Center | Jarik Lurik</title>
    <!-- React App will inject styles here -->
</head>
<body class="bg-gray-900">
    <div id="root"></div>

    <!-- Vite Dev Server Script (only for development, in production we'd use the built asset) -->
    <!-- For simplicity in this demo, we assume dev mode or need to point to built file manually -->
    <!-- Force Production Build for now since we aren't running Vite Dev Server -->
    <?php if (false && ENVIRONMENT === 'development'): ?>
        <script type="module" src="http://localhost:5173/@vite/client"></script>
        <script type="module" src="http://localhost:5173/resources/js/security-dashboard/main.tsx"></script>
    <?php else: ?>
        <?php
        $manifestPath = FCPATH . 'assets/security-dashboard/.vite/manifest.json';
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            // Vite root was set to subdirectory, so key is relative to that
            $mainFile = $manifest['main.tsx'] ?? null;
            
            if ($mainFile) {
                // Load Main JS
                echo '<script type="module" src="' . base_url('assets/security-dashboard/' . $mainFile['file']) . '"></script>';
                
                // Load CSS
                if (!empty($mainFile['css'])) {
                    foreach ($mainFile['css'] as $css) {
                        echo '<link rel="stylesheet" href="' . base_url('assets/security-dashboard/' . $css) . '">';
                    }
                }
            }
        } else {
             // Fallback or Error
             echo '<!-- Manifest not found at ' . $manifestPath . ' -->';
        }
        ?>
    <?php endif; ?>
</body>
</html>
