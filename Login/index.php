<?php 
include "../db_connect.php"

// Get the submitted username and password
$user = $_POST['username'];
$pass = $_POST['password'];

// Check if the user is an admin
$admin_sql = "SELECT * FROM admins WHERE username='$user' AND password=MD5('$pass')";
$admin_result = $conn->query($admin_sql);

if ($admin_result->num_rows > 0) {
    // Admin login successful
    $row = $admin_result->fetch_assoc();
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = 'admin';
    header("Location: admin_dashboard.php");
    exit();
}

// Check if the user is a regular user
$user_sql = "SELECT * FROM users WHERE username='$user' AND password=MD5('$pass')";
$user_result = $conn->query($user_sql);

if ($user_result->num_rows > 0) {
    // User login successful
    $row = $user_result->fetch_assoc();
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = 'user';
    header("Location:homepage.html");
    exit();
} else {
    echo "Invalid username or password!";
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login/Signup</title>
<link rel="stylesheet" href="login.css">
</head>
<body>
<div class="form-container">
  <!-- Login Form -->
  <form id="login-form" class="form" method="POST" action="auth.php">
    <h2>Login</h2>
    <input type="text" id="username" name="username" placeholder="Username" required>
    <input type="password" id="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
  
  <!-- Sign Up Form -->
  <form id="signup-form" class="form" method="POST" action="signup.php">
    <h2>Sign Up</h2>
    <input type="text" id="new-username" name="new_username" placeholder="Username" required>
    <input type="password" id="new-password" name="new_password" placeholder="Password" required>
    <button type="submit">Sign Up</button>
  </form>
</div>
</body>
</html>

