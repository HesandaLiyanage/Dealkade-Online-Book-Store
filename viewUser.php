<?php
require_once('db_connect.php');

$user_id = $_GET['id'];

// Fetch user details
$sql_user = "SELECT id, username_or_email, name, address, phone_number FROM users WHERE id = $user_id";
$result_user = $conn->query($sql_user);

// Check if user exists
if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// Fetch user orders
$sql_orders = "SELECT id, total_amount, status, created_at FROM orders WHERE user_id = $user_id";
$result_orders = $conn->query($sql_orders);

echo "
<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
    <link rel='stylesheet' href='css/admin.css'>
     <link rel='stylesheet' type='text/css' href='css/profile.css'>
</head>
<body>
     <div class='prof'>
    <h1>User Profile</h1>
    <div class='profile-details'>
        <p><h4>Name</h4> " . $user['name'] . "</p>
        <p><h4>Email</h4> " . $user['username_or_email']. "</p>
        <p><h4>Address </h4>" . $user['address']. "</p>
        <p><h4>Phone Number </h4>" . $user['phone_number'] . "</p>
        <a href='UpdateUser.php?id=" . $user['id'] ."' class='button'  >Edit User</a>
<a href='deleteUser.php?id=" . $user['id'] ."'>Delete User</a>
    </div>
</div>
    <br><br>
    <div class='orders'>
        <h2>Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>";
            
if ($result_orders->num_rows > 0) {
    while ($order = $result_orders->fetch_assoc()) {
        echo "<tr>
                    <td>" . $order['id'] . "</td>
                    <td>" . $order['total_amount'], 2 . "</td>
                    <td>" . $order['status'] . "</td>
                    <td>" . $order['created_at'] . "</td>
                  </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No orders found.</td></tr>";
}

echo "      </tbody>
        </table>
    </div>
</body>
</html>";

$conn->close();
?>
