<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'jariklurik';

try {
    $mysqli = new mysqli($host, $username, $password, $database);
    echo "<pre>";
    echo "Connected to database successfully.\n";

    // Query recent applicants
    $sql = "SELECT id, first_name, email, status, created_at FROM applicant ORDER BY id DESC LIMIT 5";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        echo "Recent Applicants:\n";
        while($row = $result->fetch_assoc()) {
            print_r($row);
        }
    } else {
        echo "No applicants found.\n";
    }
    
    // Check Status count
    $sqlCount = "SELECT status, COUNT(*) as count FROM applicant GROUP BY status";
    $resCount = $mysqli->query($sqlCount);
    echo "\nStatus Summary:\n";
    while($row = $resCount->fetch_assoc()) {
        echo "Status " . $row['status'] . ": " . $row['count'] . "\n";
    }

    echo "</pre>";

} catch (mysqli_sql_exception $e) {
    echo "Database error: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
