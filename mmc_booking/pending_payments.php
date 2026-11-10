<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "mmc_booking_system";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch ALL visitor payments with activity title
$sql = "
SELECT 
    b.booker_id,
    b.fullname,
    b.booking_date,
    b.booking_time,
    bk.activity_title,
    p.amount AS total_payment,
    p.payment_status
FROM booker_db b
JOIN payment_db p ON b.booker_id = p.booker_id
JOIN booking_db bk ON b.booker_id = bk.booker_id
WHERE b.booker_type = 'visitor'
ORDER BY b.booking_date DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pending Payments</title>
  <link rel="stylesheet" href="payment.css">
</head>
<body>
  <header class="header">
    <a href="dashboard.php" class="back-button" title="Back to Dashboard">&#8592;</a>
    <h1>Multi Media Center - Pending Payments</h1>
  </header>

  <main class="main-container">
    <h2 class="section-title">Pending Payments (Visitors Only)</h2>
    <table class="payments-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Activity</th>
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
              <td><?= htmlspecialchars($row['activity_title']) ?></td>
              <td><?= htmlspecialchars($row['booking_date']) ?></td>
              <td><?= htmlspecialchars($row['booking_time']) ?></td>
              <td><?= '₱' . number_format($row['total_payment'], 2) ?></td>
              <td><?= htmlspecialchars($row['payment_status']) ?></td>
              <td>
                <?php if ($row['payment_status'] === 'PENDING'): ?>
                  <form action="update_payment_status.php" method="POST">
                    <input type="hidden" name="booker_id" value="<?= $row['booker_id'] ?>">
                    <button type="submit">Mark as Paid</button>
                  </form>
                <?php else: ?>
                  <span style="color: green;">&#10003; PAID</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8">No pending payments found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
</body>
</html>