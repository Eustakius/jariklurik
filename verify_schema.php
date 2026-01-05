<?php
// Simple script to check column type
$db = \Config\Database::connect();
$query = $db->query("SHOW COLUMNS FROM job_vacancy LIKE 'description'");
$row = $query->getRow();
echo "Column: description, Type: " . $row->Type . "\n";

$query = $db->query("SHOW COLUMNS FROM job_vacancy LIKE 'requirement'");
$row = $query->getRow();
echo "Column: requirement, Type: " . $row->Type . "\n";
