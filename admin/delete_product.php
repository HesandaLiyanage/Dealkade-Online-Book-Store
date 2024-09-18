<?php
session_start();

if (!isset($_SESSION['role']) === 'admin') {
    echo "You aren't an admin!!!";
    exit();
}

include "../db_connect.php";

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle product deletion
if ($product_id) {
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: products.php");
exit();
?>
