<?php
session_start(); // Start the session
include "db_connect.php";

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
        $hashed_password = MD5($password);

        // Insert new user into the database
        $register_sql = "INSERT INTO users (username_or_email, password, role, name, address, phone_number) 
                         VALUES ('$username_or_email', '$hashed_password', 'customer', '$name', '$address', '$phone_number')";

        if ($conn->query($register_sql) === TRUE) {
            // Registration successful, get the user ID of the newly created user
            $user_id = $conn->insert_id;

            // Set session variables with user_id and role
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = 'customer'; // Default role is customer

            // Redirect to the customer home page
            header("Location: ../customer/index.php");
            exit();
        } else {
            $error = "Error in registration: " . $conn->error;
        }
    }
}

// Login Section
if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Check if the user exists and password is correct
    $user_sql = "SELECT * FROM users WHERE username_or_email='$user' AND password=MD5('$pass')";
    $user_result = $conn->query($user_sql);

    if ($user_result->num_rows > 0) {
        // User login successful
        $row = $user_result->fetch_assoc();

        // Store user ID and role in the session
        $_SESSION['user_id'] = $row['id']; // Assuming the user ID column is 'id'
        $_SESSION['role'] = $row['role']; // Storing the role (admin/customer)

        // Redirect based on the user's role
        if ($row['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: customer/index.php");
        }
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
<link rel="stylesheet" href="css/login.css">
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
    <button type="submit" name="register">Sign Up</button>
  </form>
</div>
</body>
</html>
