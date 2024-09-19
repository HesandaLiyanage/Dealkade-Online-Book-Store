<?php
include "../db_connect.php";

$user_id = $_GET['id'];

// Delete user from the database

if ($user_id) {
    // Start a transaction to ensure all queries execute successfully
    $conn->begin_transaction();

    try {
        // SQL statements
        $sql1 = "DELETE FROM reviews WHERE user_id = $user_id;";
        $sql2 = "DELETE FROM cart_items WHERE cart_id IN (SELECT id FROM cart WHERE user_id = $user_id);";
        $sql3 = "DELETE FROM orders_items WHERE order_id IN (SELECT id FROM orders WHERE user_id = $user_id);";
        $sql4 = "DELETE FROM orders WHERE user_id = $user_id;";
        $sql5 = "DELETE FROM cart WHERE user_id = $user_id;";
        $sql6 = "DELETE FROM users WHERE id = $user_id;";

        // Prepare and execute the first query
        $stmt = $conn->prepare($sql1);
        if (!$stmt->execute()) echo "Failed to delete from reviews: " . $conn->error . "<br>";
        $stmt->close();

        // Prepare and execute the second query
        $stmt = $conn->prepare($sql2);
        if (!$stmt->execute()) echo "Failed to delete from cart_items: " . $conn->error . "<br>";
        $stmt->close();

        // Prepare and execute the third query
        $stmt = $conn->prepare($sql3);
        if (!$stmt->execute()) echo "Failed to delete from order_items: " . $conn->error . "<br>";
        $stmt->close();

        // Prepare and execute the final query (delete product)
        $stmt = $conn->prepare($sql4);
        if (!$stmt->execute()) echo "Failed to delete from orders: " . $conn->error . "<br>";
        $stmt->close();

        $stmt = $conn->prepare($sql5);
        if (!$stmt->execute()) echo "Failed to delete from carts: " . $conn->error . "<br>";
        $stmt->close();

        $stmt = $conn->prepare($sql6);
        if (!$stmt->execute()) echo "Failed to delete from users: " . $conn->error . "<br>";
        $stmt->close();

        // Commit the transaction if all queries succeed
        $conn->commit();
        echo "Deletion successful!"; 
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        echo "Error deleting product: " . $e->getMessage();
    }
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
