<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MMC Booking System - Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Create Account</h2>
        <form id="register-form" action="register_submit.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Choose a username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Create a password" required>

            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm password" required>

            <button type="submit">Register</button>

            <p style="text-align: center; margin-top: 10px;">
                Already have an account? <a href="loginform.php">Login here</a>
            </p>
        </form> 
    </div>
</body>
</html>
