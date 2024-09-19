<?php
require_once('db_connect.php');

$user_id = $_GET['id'];

// Delete user from the database

$sql1="DELETE FROM cart_items WHERE cart_id IN (SELECT id FROM cart WHERE user_id = $user_id)";


$sql2="DELETE FROM cart WHERE user_id = $user_id";


$sql3="DELETE FROM orders WHERE user_id = $user_id";

$sql4="DELETE FROM users WHERE id = $user_id";

if ($conn->query($sql4) === TRUE) {
    echo "User deleted successfully.";
    header("Location:admin.php");
} else {
    echo "Error deleting user: " . $conn->error;
}

$conn->close();

// Redirect to the admin dashboard after deletion
header("Location: admin.php");
?>