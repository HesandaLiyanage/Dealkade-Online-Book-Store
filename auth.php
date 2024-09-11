<?php
// Start session
session_start();

// Database connection settings
$servername = "localhost";

$username = "root";
$password = "";

$dbname = "Shopfusion";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
