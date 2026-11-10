<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "mmc_booking_system";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booker_id'])) {
    $booker_id = $_POST['booker_id'];

    $update = "UPDATE payment_db SET payment_status = 'DONE', payment_date = NOW() WHERE booker_id = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("i", $booker_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: pending_payments.php");
exit();
