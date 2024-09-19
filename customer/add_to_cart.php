<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/index.php"); // Redirect to login if not logged in
    exit();
}

// Database connection
include '../db_connect.php'; // Make sure this file includes your database connection

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$price = $_POST['price'];
$quantity = 1; // Default quantity

// Get the cart ID for the current user
$query = "SELECT id FROM cart WHERE user_id = $user_id";
$result = $conn->prepare($query);
$cart = $result->fetch_assoc();

if ($cart) {
    $cart_id = $cart['id'];
} else {
    // Create a new cart if one does not exist
    $query = "INSERT INTO cart (user_id) VALUES ($user_id)";
    $conn->query($query);
    $cart_id = $conn->insert_id;
}

// Check if the item is already in the cart
$query = "SELECT id FROM cart_items WHERE cart_id = $cart_id AND product_id = $product_id";
$result = $conn->query($query);
$item = $result->fetch_assoc();

if ($item) {
    // Update quantity if item already exists in cart
    $query = "UPDATE cart_items SET quantity = quantity + $quantity WHERE cart_id = $cart_id AND product_id = $product_id";
    $conn->query($query);
} else {
    // Insert new item into cart
    $query = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES ($cart_id, $product_id, $quantity)";
    $conn->query($query);
}

header("Location: cart.php"); // Redirect to cart page
exit();
