<?php 
session_start();
require_once('connect.php');
include "header.php";

// Debugging: Check if session variable is set
if (!isset($_SESSION['username_or_email'])) {
    echo "<p>Session variable 'username_or_email' is not set.</p>";
    exit();
}

// Get the username or email from the session
$username_or_email = $_SESSION['username_or_email'];

// Step 1: Fetch user details
$sql_user = "SELECT id, username_or_email, password, name, address, phone_number FROM users WHERE username_or_email = '$username_or_email'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $user_details = $result_user->fetch_assoc();
    $user_id = $user_details['id']; // Fetch user ID

    // Display user details
    echo "<div class='prof'>
            <h1>My Profile</h1>
            <p>Name: " . htmlspecialchars($user_details['name']) . "</p>
            <p>Email: " . htmlspecialchars($user_details['username_or_email']) . "</p>
            <p>Address: " . htmlspecialchars($user_details['address']) . "</p>
            <p>Phone Number: " . htmlspecialchars($user_details['phone_number']) . "</p>
          </div>";

    // Step 2: Fetch user orders
    $sql_orders = "SELECT id, total_amount, status, created_at FROM orders WHERE user_id = $user_id";
    $result_orders = $conn->query($sql_orders);

    if ($result_orders->num_rows > 0) {
        echo "<div class='orders'>
                <h2>My Orders</h2>
                <table>
                    <tr>
                        <th>Order ID</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>";
        while ($order = $result_orders->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($order['id']) . "</td>
                    <td>$" . number_format($order['total_amount'], 2) . "</td>
                    <td>" . htmlspecialchars($order['status']) . "</td>
                    <td>" . htmlspecialchars($order['created_at']) . "</td>
                  </tr>";
        }
        echo "  </table>
              </div>";
    } else {
        echo "<p>You have no orders.</p>";
    }
} else {
    echo "<p>User not found.</p>";
}

$conn->close();
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/profile.css">
</head>
</html>
