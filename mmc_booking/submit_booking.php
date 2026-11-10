<?php
session_start(); // Start session at the beginning

$host = "localhost";
$username = "root";
$password = "";
$database = "mmc_booking_system";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get admin_id from session (null if not logged in as admin)
$admin_id = $_SESSION['admin_id'] ?? null;

// Collect POST data
$fullname = $_POST['full_name'] ?? $_POST['full-name'] ?? '';
$phone = $_POST['phone'] ?? $_POST['phone-number'] ?? '';
$booker_type = $_POST['booker_type'] ?? 'student';
$booking_date = $_POST['booking_date'] ?? '';
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$activity = $_POST['activity_title'] ?? $_POST['activity-title'] ?? '';
$department = $_POST['department'] ?? null;
$id_number = $_POST['id_number'] ?? $_POST['id-number'] ?? null;
$total_payment = $_POST['total_payment'] ?? 0;

// Insert into booker_db
$booker_sql = "INSERT INTO booker_db (fullname, phone, booker_type, booking_date, booking_time)
               VALUES (?, ?, ?, ?, ?)";
$booker_stmt = $conn->prepare($booker_sql);
if (!$booker_stmt) {
    die("Error preparing statement: " . $conn->error);
}

$booking_time_display = date('h:i A', strtotime($start_time)) . ' - ' . date('h:i A', strtotime($end_time));
$booker_stmt->bind_param("sssss", $fullname, $phone, $booker_type, $booking_date, $booking_time_display);
if (!$booker_stmt->execute()) {
    die("Error executing statement: " . $booker_stmt->error);
}
$booker_id = $conn->insert_id;
$booker_stmt->close();

// Insert into booking_db (including admin_id)
$status = 'pending';
$created_at = date("Y-m-d H:i:s");

$booking_sql = "INSERT INTO booking_db (admin_id, booker_id, booking_date, start_time, end_time, status, created_at, activity_title, department, id_number)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$booking_stmt = $conn->prepare($booking_sql);
if (!$booking_stmt) {
    die("Error preparing statement: " . $conn->error);
}

// Bind parameters (admin_id can be null)
$booking_stmt->bind_param("iissssssss", $admin_id, $booker_id, $booking_date, $start_time, $end_time, $status, $created_at, $activity, $department, $id_number);
if (!$booking_stmt->execute()) {
    die("Error executing statement: " . $booking_stmt->error);
}
$booking_stmt->close();

// Insert payment for visitors only
if ($booker_type === 'visitor') {
    $payment_sql = "INSERT INTO payment_db (booker_id, amount, payment_status, payment_date)
                    VALUES (?, ?, 'PENDING', NULL)";
    $payment_stmt = $conn->prepare($payment_sql);
    if (!$payment_stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $payment_stmt->bind_param("id", $booker_id, $total_payment);
    if (!$payment_stmt->execute()) {
        die("Error executing statement: " . $payment_stmt->error);
    }
    $payment_stmt->close();
}

$conn->close();
header("Location: manage_booking.php");
exit();
?>