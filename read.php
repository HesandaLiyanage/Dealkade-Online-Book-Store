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
    <div class='profile-details'>
        <p><h4>Name</h4> " . $user_details['name'] . "</p>
        <p><h4>Email</h4> " . $user_details['username_or_email']. "</p>
        <p><h4>Address </h4>" . $user_details['address']. "</p>
        <p><h4>Phone Number </h4>" . $user_details['phone_number'] . "</p>
    </div>
</div>";


    // Step 2: Fetch user orders
    $sql_orders = "SELECT id, total_amount, status, created_at FROM orders WHERE user_id = $user_id";
    $result_orders = $conn->query($sql_orders);

    echo "<div class='orders'>
                <h2>My Orders</h2>
                <table>
                    <tr>
                        <th>Order ID</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>";

    if ($result_orders->num_rows > 0) {
        
        while ($order = $result_orders->fetch_assoc()) {
            echo "<tr>
                    <td>" . $order['id'] . "</td>
                    <td>" . $order['total_amount'], 2 . "</td>
                    <td>" . $order['status'] . "</td>
                    <td>" . $order['created_at'] . "</td>
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
