<?php
// db_connect.php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT admin_id, username, password FROM admin_db WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['admin_id']; // Store admin_id in session
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- Your original HTML form below -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MMC Booking System - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Multi Media Center</h2>
        <form id="login-form" method="POST" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Remember Me
                </label>
                <a href="#" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit">Login</button>
            <p style="text-align: center; margin-top: 15px;">
                Don't have an account? <a href="register.php">Register now</a>
            </p>
        </form>
    </div>
</body>
</html>
