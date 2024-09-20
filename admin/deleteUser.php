<?php
session_start();
include "../db_connect.php";

$user_id = $_GET['id'];

// Delete user from the 
if ($_SESSION['role'] !== 'admin') {
    echo "<p>Access denied.</p>";
    exit();
}

if ($user_id) {
    // SQL statements
    $sql1 = "DELETE FROM reviews WHERE user_id = $user_id;";
    $sql2 = "DELETE FROM cart_items WHERE cart_id IN (SELECT id FROM cart WHERE user_id = $user_id);";
    $sql3 = "DELETE FROM orders_items WHERE order_id IN (SELECT id FROM orders WHERE user_id = $user_id);";
    $sql4 = "DELETE FROM orders WHERE user_id = $user_id;";
    $sql5 = "DELETE FROM cart WHERE user_id = $user_id;";
    $sql6 = "DELETE FROM users WHERE id = $user_id;";

    // Execute queries
    $conn->query($sql1);
    $conn->query($sql2);
    $conn->query($sql3);
    $conn->query($sql4);
    $conn->query($sql5);
    $conn->query($sql6);

    echo "Deletion successful!";
    exit();
}











// if ($conn->query($sql_delete) === TRUE) {
//     echo "User deleted successfully.";
//     header("Location:admin.php");
// } else {
//     echo "Error deleting user: " . $conn->error;
// }

$conn->close();

// Redirect to the admin dashboard after deletion
header("Location: updateUser.php");
?>
