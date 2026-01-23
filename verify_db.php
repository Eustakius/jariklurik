<?php
$mysqli = new mysqli("localhost", "root", "", "jariklurik");
if ($mysqli->connect_error) {
    file_put_contents("db_status.txt", "ERROR: " . $mysqli->connect_error);
    exit;
}
$result = $mysqli->query("SHOW COLUMNS FROM web_visitors LIKE 'device_type'");
if ($result && $result->num_rows > 0) {
    file_put_contents("db_status.txt", "YES");
} else {
    file_put_contents("db_status.txt", "NO");
}
$mysqli->close();
?>
