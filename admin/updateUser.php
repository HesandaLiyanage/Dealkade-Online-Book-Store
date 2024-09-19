<?php
include "../db_connect.php";

$user_id = $_GET['id'];

// Fetch user details for pre-filling the form
$sql_user = "SELECT id, username_or_email, name, address, phone_number FROM users WHERE id = '$user_id' ";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];

    // Update user in the database
    $sql_update = "UPDATE users SET name='$name', address='$address', phone_number='$phone_number' WHERE id=$user_id";

    if ($conn->query($sql_update) === TRUE) {
        echo "User updated successfully.";
    } else {
        echo "Error updating user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/updateuser.css">
</head>
<body>
    <div class="edit-user">
        <h1>Edit User</h1><br>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required><br>
            <br>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo $user['address']; ?>" required><br>
            <br>
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo $user['phone_number']; ?>" required><br>
            <br>
            <button type="submit">Update User</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
