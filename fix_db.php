<?php
// Script to check and fix database schema manually if migration fails
$mysqli = new mysqli("localhost", "root", "", "jariklurik");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if device_type column exists
$result = $mysqli->query("SHOW COLUMNS FROM web_visitors LIKE 'device_type'");
if ($result->num_rows == 0) {
    echo "Column device_type missing. Adding it...\n";
    $sql = "ALTER TABLE web_visitors ADD COLUMN device_type ENUM('Desktop', 'Mobile', 'Tablet', 'Unknown') NOT NULL DEFAULT 'Unknown' AFTER device_fingerprint";
    if ($mysqli->query($sql) === TRUE) {
        echo "Column device_type added successfully.\n";
    } else {
        echo "Error adding column: " . $mysqli->error . "\n";
    }
} else {
    echo "Column device_type already exists.\n";
}

$mysqli->close();
?>
