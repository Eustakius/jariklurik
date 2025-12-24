<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "jariklurik";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT status, COUNT(*) as count FROM purna_pmi GROUP BY status";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "Status: " . $row["status"]. " - Count: " . $row["count"]. "\n";
      }
    } else {
      echo "0 results in purna_pmi";
    }
} else {
    echo "Error: " . $conn->error;
}
$conn->close();
?>
