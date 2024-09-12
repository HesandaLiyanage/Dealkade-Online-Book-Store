<?php 
include "../db_connect.php";

$error = '';

// Get the submitted username and password
if (isset($_POST['username']) && isset($_POST['password'])) {
$user = $_POST['username'];
$pass = $_POST['password'];

// // Check if the user is an admin
// $admin_sql = "SELECT * FROM admins WHERE username='$user' AND password=MD5('$pass')";
// $admin_result = $conn->query($admin_sql);

// if ($admin_result->num_rows > 0) {
//     // Admin login successful
//     $row = $admin_result->fetch_assoc();
//     $_SESSION['username'] = $row['username'];
//     $_SESSION['role'] = 'admin';
//     header("Location: admin_dashboard.php");
//     exit();
// }

// Check if the user is a regular user
$user_sql = "SELECT * FROM users WHERE username_or_email='$user' AND password=MD5('$pass')";
$user_result = $conn->query($user_sql);

if ($user_result->num_rows > 0) {
    // User login successful
    $row = $user_result->fetch_assoc();
    $_SESSION['username_or_email'] = $row['username_or_email'];
    $_SESSION['role'] = 'user';
    $sql = "UPDATE users SET role='$customer'";
    header("Location:homepage.html");
    exit();
} else {
    $error = "Invalid username or password!";
}

$conn->close();
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login/Signup</title>
<link rel="stylesheet" href="index.css">
<style>
  .error {
    color: red;
    font-size: 16px;
    margin-bottom: 10px;
  }
</style>
</head>
<body>
<div class="form-container">
  <!-- Login Form -->
  <form id="login-form" class="form" method="POST" action="index.php">
    <h2>Login</h2>
    <div class="error">
      <?php 
      if (!empty($error)): ?>
      <?php
      echo $error;
      ?>
      <?php
      endif;
      ?>
    </div>
    <input type="text" id="username" name="username" placeholder="Username" required>
    <input type="password" id="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
  
  <!-- Sign Up Form -->
  <form id="signup-form" class="form" method="POST" action="index.php">
    <h2>Sign Up</h2>
    <input type="text" id="new-username" name="username_or_email" placeholder="Username/Email" required>
    <input type="password" id="new-password" name="password" placeholder="Password" required>
    <input type="text" id="name" name="name" placeholder="Name" required>
    <input type="text" id="address" name="address" placeholder="Address" required>
    <input type="text" id="tel" name="phone_number" placeholder="Telephone_Number" required>
    <button type="submit">Sign Up</button>
  </form>
</div>
</body>
</html>
