<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "erp_local";   // Use the database name you created

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
