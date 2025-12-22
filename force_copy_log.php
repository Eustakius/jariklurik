<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$logFile = 'd:/ATMAJAYA/Semester 6/PBO/Object Presistence/XAMPP/htdocs/jariklurik/force_copy.log';
$sourceBase = 'd:/ATMAJAYA/Semester 6/PBO/Object Presistence/XAMPP/htdocs/jariklurik/public_html/assets/images/company/logo/';
$destBase = 'd:/ATMAJAYA/Semester 6/PBO/Object Presistence/XAMPP/htdocs/jariklurik/ci/public/assets/images/company/logo/';

$files = [
    'pt-duta-wibawa-manda-putra-1761039086.jpg',
    'pt-haena-duta-cemerlang-cabang-yogyakarta-1760605470.png',
    'joyous-mediation-co-ltd-1762702346.jpg'
];

$output = "Starting copy at " . date('Y-m-d H:i:s') . "\n";

foreach ($files as $file) {
    $src = $sourceBase . $file;
    $dst = $destBase . $file;

    $output .= "Copying $file...\n";
    
    if (!file_exists($src)) {
        $output .= "  ERROR: Source missing: $src\n";
        continue;
    }

    if (!is_dir(dirname($dst))) {
        mkdir(dirname($dst), 0777, true);
        $output .= "  Created dir: " . dirname($dst) . "\n";
    }

    if (copy($src, $dst)) {
        $output .= "  SUCCESS.\n";
    } else {
        $output .= "  FAILED: " . print_r(error_get_last(), true) . "\n";
    }
}

$output .= "Finished.\n";
file_put_contents($logFile, $output);
echo "Log written to $logFile";
