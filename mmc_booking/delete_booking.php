<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "mmc_booking_system";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$booker_id = $_POST['booker_id'] ?? 0;

if ($booker_id > 0) {
  // Delete related records
  $conn->query("DELETE FROM payment_db WHERE booker_id = $booker_id");
  $conn->query("DELETE FROM booking_db WHERE booker_id = $booker_id");
  $conn->query("DELETE FROM booker_db WHERE booker_id = $booker_id");
}

$conn->close();
header("Location: manage_booking.php");
exit();
?>
