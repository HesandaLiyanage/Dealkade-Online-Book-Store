<?php 
include "../db_connect.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
  $username_or_email = $_POST['username_or_email'];
  $password = $_POST['password'];
  $name = $_POST['name'];
  $address = $_POST['address'];
  $phone_number = $_POST['phone_number'];

  // Check if the username or email already exists
  $check_sql = "SELECT * FROM users WHERE username_or_email='$username_or_email'";
  $check_result = $conn->query($check_sql);

  if ($check_result->num_rows > 0) {
      // Username or email already exists
      $error = "Username or email is already registered!";
  } else {
      // Hash the password
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Insert new user into the database
      $register_sql = "INSERT INTO users (username_or_email, password, role, name, address, phone_number) 
                       VALUES ('$username_or_email', '$hashed_password', 'customer', '$name', '$address', '$phone_number')";

      if ($conn->query($register_sql) === TRUE) {
          // Registration successful, redirect to login or homepage
          $_SESSION['username_or_email'] = $username_or_email;
          $_SESSION['role'] = 'customer';
          header("Location: homepage.html");
          exit();
      } else {
          $error = "Error in registration: " . $conn->error;
      }
  }
}

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
