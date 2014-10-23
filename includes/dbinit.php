<?php
// Define connection variables
$DBServer = "localhost";  // server name or IP address
$DBUser = "root";
$DBPass = "freebsd";
$DBName = "markerlocations";

// Create connection
$conn = new mysqli($DBServer, $DBUser, $DBPass, $DBName);

// Check connection
if ($conn->connect_error) {
    echo "Database connection failed: " . $conn->connect_error, E_USER_ERROR;
}
?>