<?php

// Check Permissions Script
// Run with: php tools/check_permissions.php

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Load CI4
$minPhpVersion = '8.1'; 
define('FCPATH', __DIR__ . '/../public' . DIRECTORY_SEPARATOR);
chdir(__DIR__ . '/../');
require __DIR__ . '/../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
exit(\CodeIgniter\Boot::bootWeb($paths));

// We need to inject our own logic after boot, but bootWeb exits. 
// So actually we should just bootstrap normally like Spark does.
// Re-attempting standard spark bootstrap approach for CLI scripts.
