<?php
// Function definitions from Common.php
function base32url_decode($data) {
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

function shortDecrypt($hash, $key = 'rardianto') {
    $hash = strtolower($hash);
    $data = base32url_decode($hash);
    $out = '';
    for ($i = 0; $i < strlen($data); $i++) {
        $out .= chr(ord($data[$i]) ^ ord($key[$i % strlen($key)]));
    }
    return $out;
}

// Params
$hash = 'i5mq';
$secret = 'rardianto'; // default from .env
$id = shortDecrypt($hash, $secret);

echo "Hash: $hash\n";
echo "Decrypted ID: '$id'\n";

// DB Query
$host = 'localhost';
$db   = 'jariklurik';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connected to DB.\n";
    
    // Check column existence
    $colStmt = $pdo->query("SHOW COLUMNS FROM job_vacancy LIKE 'required_documents'");
    $col = $colStmt->fetch();
    echo "Column 'required_documents': " . ($col ? "EXISTS" : "MISSING") . "\n";
    
    if ($col) {
        $stmt = $pdo->prepare("SELECT id, position, required_documents FROM job_vacancy WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        
        if ($row) {
            echo "Job Found: " . $row['position'] . "\n";
            echo "required_documents (raw): '" . $row['required_documents'] . "'\n";
            $json = json_decode($row['required_documents'] ?? '', true);
            echo "required_documents (decoded): " . print_r($json, true) . "\n";
        } else {
            echo "Job ID $id NOT FOUND.\n";
            
            // List latest to see if ID is close
            echo "Latest 5 jobs:\n";
            $latest = $pdo->query("SELECT id, position FROM job_vacancy ORDER BY id DESC LIMIT 5")->fetchAll();
            print_r($latest);
        }
    }

} catch (\PDOException $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}
