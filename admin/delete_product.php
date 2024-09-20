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
    // SQL statements
    $sql1 = "DELETE FROM reviews WHERE product_id = $product_id;";
    $sql2 = "DELETE FROM product_images WHERE product_id = $product_id;";
    $sql3 = "DELETE FROM orders_items WHERE product_id = $product_id;";
    $sql4 = "DELETE FROM cart_items WHERE product_id = $product_id;";
    $sql5 = "DELETE FROM products WHERE id = $product_id;";

    // Execute queries
    $conn->query($sql1);
    $conn->query($sql2);
    $conn->query($sql3);
    $conn->query($sql4);
    $conn->query($sql5);

    echo "Deletion successful!";
    exit();
}


$conn->close();

header("Location: products.php");
exit();
?>
