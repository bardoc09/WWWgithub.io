<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "mmc_booking_system";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all bookings
$sql = "
SELECT 
    b.booker_id,
    b.fullname,
    b.booker_type,
    b.booking_date,
    b.booking_time,
    p.amount AS total_payment,
    p.payment_status
FROM booker_db b
LEFT JOIN payment_db p ON b.booker_id = p.booker_id
ORDER BY b.booking_date DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Booking</title>
  <link rel="stylesheet" href="payment.css">
  <style>
    .action-buttons a {
      margin-right: 10px;
      text-decoration: none;
      font-weight: bold;
      color: #007bff;
    }
    .action-buttons form {
      display: inline;
    }
    .action-buttons button {
      background-color: #e74c3c;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <header class="header">
    <a href="dashboard.php" class="back-button" title="Back to dashboard">&#8592;</a>
    <h1>Multi Media Center</h1>
  </header>

  <main class="main-container">
    <h2 class="section-title">Manage Booking</h2>
    <table class="payments-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Date</th>
          <th>Time</th>
          <th>Total Payment</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['booker_id']) ?></td>
              <td><?= htmlspecialchars($row['fullname']) ?></td>
              <td><?= htmlspecialchars($row['booking_date']) ?></td>
              <td><?= htmlspecialchars($row['booking_time']) ?></td>
              <td><?= $row['total_payment'] ? '₱' . number_format($row['total_payment'], 2) : '₱0.00' ?></td>
              <td><?= htmlspecialchars($row['payment_status'] ?? 'PENDING') ?></td>
              <td>
                <div class="action-buttons">
                  <!-- Change button -->
                  <a href="<?= $row['booker_type'] === 'visitor' ? 'booking-visitor.php?id=' . $row['booker_id'] : 'student_staff_booking_form.php?id=' . $row['booker_id'] ?>">
                    Change
                  </a>

                  <!-- Delete button -->
                  <form method="POST" action="delete_booking.php" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                    <input type="hidden" name="booker_id" value="<?= $row['booker_id'] ?>">
                    <button type="submit">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7">No pending bookings found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
</body>
</html>
