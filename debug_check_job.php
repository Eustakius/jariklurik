<?php
// Load CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(__DIR__);
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';

use App\Models\JobVacancyModel;

// Helper need for shortDecrypt
helper(['text', 'encryption']); 

$id_enc = 'i5mq';
$id_dec = shortDecrypt($id_enc);

echo "Encrypted ID: $id_enc\n";
echo "Decrypted ID: " . var_export($id_dec, true) . "\n";

if (!is_numeric((int)$id_dec)) {
    echo "Decryption failed or invalid ID.\n";
    exit;
}

$model = new JobVacancyModel();
$job = $model->find((int)$id_dec);

echo "Job Found: " . ($job ? "YES" : "NO") . "\n";

if ($job) {
    echo "ID: " . $job->id . "\n";
    echo "Status: " . $job->status . "\n";
    echo "Deleted At: " . var_export($job->deleted_at, true) . "\n";
    echo "Selection Date: " . $job->selection_date . "\n";
    
    // Check required_documents
    echo "Required Documents (Raw): " . var_export($job->required_documents, true) . "\n";
    
    // Check if hydration works
    try {
        echo "Required Documents (Access): " . var_export($job->required_documents, true) . "\n";
    } catch (\Exception $e) {
        echo "Error Accessing required_documents: " . $e->getMessage() . "\n";
    }

    // Check Frontend Format Method
    try {
        $formatted = $job->formatDataFrontendDetailModel();
        echo "Format Frontend Detail: Success\n";
        print_r($formatted);
    } catch (\Exception $e) {
        echo "Format Frontend Detail Error: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString();
    }
} else {
    echo "Job with ID $id_dec not found in DB.\n";
}
