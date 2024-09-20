<?php
session_start();

if ($_SESSION['role'] !== 'admin') {
    echo "<p>Access denied.</p>";
    exit();
}

include "../db_connect.php";

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle product deletion
if ($product_id) {
    // Start a transaction to ensure all queries execute successfully
    $conn->begin_transaction();

    try {
        // SQL statements
        $sql1 = "DELETE FROM reviews WHERE product_id = $product_id;";
        $sql2 = "DELETE FROM product_images WHERE product_id = $product_id;";
        $sql3 = "DELETE FROM orders_items WHERE product_id = $product_id;";
        $sql4 = "DELETE FROM cart_items WHERE product_id = $product_id;";
        $sql5 = "DELETE FROM products WHERE id = $product_id;";

        // Prepare and execute the first query
        $stmt = $conn->prepare($sql1);
        if (!$stmt->execute()) echo "Failed to delete from reviews: " . $conn->error . "<br>";
        $stmt->close();

        // Prepare and execute the second query
        $stmt = $conn->prepare($sql2);
        if (!$stmt->execute()) echo "Failed to delete from product_images: " . $conn->error . "<br>";
        $stmt->close();

        // Prepare and execute the third query
        $stmt = $conn->prepare($sql3);
        if (!$stmt->execute()) echo "Failed to delete from orders_items: " . $conn->error . "<br>";
        $stmt->close();

        // Prepare and execute the final query (delete product)
        $stmt = $conn->prepare($sql4);
        if (!$stmt->execute()) echo "Failed to delete from cart_items: " . $conn->error . "<br>";
        $stmt->close();

        $stmt = $conn->prepare($sql5);
        if (!$stmt->execute()) echo "Failed to delete from products: " . $conn->error . "<br>";
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

$conn->close();

header("Location: products.php");
exit();
?>
