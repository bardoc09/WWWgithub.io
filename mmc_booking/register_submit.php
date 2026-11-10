<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // Check if user exists
    $check = $conn->prepare("SELECT * FROM admin_db WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "Username already taken.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO admin_db (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $email);
        
        if ($stmt->execute()) {
            header("Location: loginform.php"); // or loginform.html if you don’t rename it
        } else {
            echo "Error: " . $conn->error;
        }
    }
    $check->close();
    $conn->close();
}
?>
