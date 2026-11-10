<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Multi Media Center Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <header class="header">
    <h1>Multi Media Center</h1>
    <div class="user-profile">
      <span class="user-icon">👤</span>
    
      </select>
    </div>
  </header>
  <main class="main-content">
    <div class="buttons-container">
      <!-- Request MMC Button -->
      <button class="dashboard-btn" onclick="navigateToClassification()">Request MMC</button>
      <button class="dashboard-btn" onclick="navigateToReschedule()">Manage bookings</button>
      <button class="dashboard-btn" onclick="window.location.href='pending_payments.php'">Pending Payments</button>
      <button class="dashboard-btn">Reports & Analytics</button>
      <button class="dashboard-btn" onclick="logout()">Logout</button>
    </div>
    </div>
  </main>

  <script>
    // Redirect to classification
    function navigateToClassification() {
      window.location.href = "classification.html";
    }

    function navigateToReschedule() {
  window.location.href = "manage_booking.php"; 
}
    // Redirect to login form when Logout is clicked
    function logout() {
      window.location.href = "loginform.php"; // ✅ change this to your actual login page
    }
  </script>
  

  <!-- Embed JavaScript directly in the HTML file -->
  <script>
    // Function to redirect to Classification page
    function navigateToClassification() {
        window.location.href = "classification.html"; // Redirect to Classification Page
    }
  </script>
</body>
</html>

