<?php
require_once('connect.php');

$user_id = $_GET['id'];

// Delete user from the database
$sql_delete = "DELETE FROM users WHERE id = $user_id";

if ($conn->query($sql_delete) === TRUE) {
    echo "User deleted successfully.";
    header("Location:admin.php");
} else {
    echo "Error deleting user: " . $conn->error;
}

$conn->close();

// Redirect to the admin dashboard after deletion
header("Location: admin_dashboard.php");
?>