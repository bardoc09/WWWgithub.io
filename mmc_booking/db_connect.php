<?php
$host = "localhost";
$dbname = "mmc_booking_system";
$username = "root"; // default for XAMPP
$password = "";     // default no password

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
