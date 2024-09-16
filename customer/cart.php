<?php
session_start();
require '../db_connect.php'; // Include your DB connection

$user_id = $_SESSION['user_id']; // Get logged-in user ID from session

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/index.php"); // Redirect to login if not logged in
    exit();
}
// Function to get the user's cart
function getCart($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = $user_id");
    // $stmt->bind_param('i', $user_id); enable this if you want to remove sql injection attacks
    $stmt->execute();
    $result = $stmt->get_result();
    $cart = $result->fetch_assoc();

    // If the user doesn't have a cart, create one
    if (!$cart) {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, created_at, updated_at) VALUES ($user_id, NOW(), NOW())");
        // $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $cart_id = $conn->insert_id;
    } else {
        $cart_id = $cart['id'];
    }

    return $cart_id;
}

// Function to get cart items
function getCartItems($conn, $cart_id) {
    $stmt = $conn->prepare("SELECT ci.id as cart_item_id, p.name, p.price, ci.quantity, ci.product_id
                            FROM cart_items ci
                            JOIN products p ON ci.product_id = p.id
                            WHERE ci.cart_id = $cart_id");
    // $stmt->bind_param('i', $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }
    return $cartItems;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = getCart($conn, $user_id);

    if (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Update the quantity of the product
        $stmt = $conn->prepare("UPDATE cart_items SET quantity = $quantity, updated_at = NOW() WHERE cart_id = $cart_id AND product_id = $product_id ");
        // $stmt->bind_param('iii', $quantity, $cart_id, $product_id);
        $stmt->execute();
    }

    if (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];

        // Remove the product from the cart
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = $cart_id AND product_id = $product_id");
        // $stmt->bind_param('ii', $cart_id, $product_id);
        $stmt->execute();
    }

    if (isset($_POST['checkout'])) {
        $totalPrice = 0;
        $cartItems = getCartItems($conn, $cart_id);

        // Calculate total price
        foreach ($cartItems as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Insert order into orders table
        $status = "shipped"; // Static status for now
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, created_at, updated_at)
                                VALUES ($user_id, $totalPrice, $status, NOW(), NOW())");
        // $stmt->bind_param('ids', $user_id, $totalPrice, $status);
        $stmt->execute();
        $order_id = $conn->insert_id;

        // Insert each cart item into order_items table
        foreach ($cartItems as $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase, created_at, updated_at)
                                    VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']}, NOW(), NOW())");
            // $stmt->bind_param('iiid', $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        // Clear the cart after checkout
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = $cart_id");
        // $stmt->bind_param('i', $cart_id);
        $stmt->execute();

        echo "<script>alert('Thank you for your purchase! Your order has been placed.');</script>";
    }
}

// Fetch cart and items
$cart_id = getCart($conn, $user_id);
$cartItems = getCartItems($conn, $cart_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 5px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 5px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .item-details {
            display: flex;
            align-items: center;
        }
        .item-image {
            width: 150px;
            height: 200px;
            border-radius: 10px;
            margin-right: 20px;
        }
        .item-info h4 {
            margin: 0;
            font-size: 18px;
        }
        .item-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #777;
        }
        .item-price, .item-quantity, .item-total {
            font-size: 16px;
        }
        .item-quantity input {
            width: 50px;
            text-align: center;
        }
        .item-actions {
            display: flex;
            align-items: center;
        }
        .item-actions button {
            background-color: #f00;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .cart-summary {
            text-align: right;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .checkout-form {
            margin-top: 20px;
        }
        .checkout-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .checkout-form button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout-form button:hover {
            background-color: #45a049;
        }
        footer {
            text-align: center;
            padding: 0.1px; /* Reduced padding */
            background-color: #333;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        .cart-item { margin-bottom: 20px; display: flex; align-items: center; }
        .item-details { flex: 1; display: flex; align-items: center; }
        .item-info { margin-left: 10px; }
        .item-price, .item-quantity, .item-total { margin-right: 20px; }
        .item-actions { margin-left: 20px; }
    </style>
</head>
<body>

<!-- <h2>Your Cart</h2>

<div id="cartItems">
    <?php if (count($cartItems) > 0): ?>
        <?php
        $totalPrice = 0;
        foreach ($cartItems as $item):
            $itemTotal = $item['price'] * $item['quantity'];
            $totalPrice += $itemTotal;
        ?>
        <div class="cart-item">
            <div class="item-details">
                <img src="https://via.placeholder.com/150x200" alt="<?php echo $item['name']; ?>" class="item-image">
                <div class="item-info">
                    <h4 class="item-name"><?php echo $item['name']; ?></h4>
                </div>
            </div>
            <div class="item-price">$<?php echo number_format($item['price'], 2); ?></div>
            <div class="item-quantity">
                <form method="post">
                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                    <button type="submit" name="update_quantity">Update</button>
                </form>
            </div>
            <div class="item-total">$<?php echo number_format($itemTotal, 2); ?></div>
            <div class="item-actions">
                <form method="post">
                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                    <button type="submit" name="remove_from_cart">Remove</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

<div id="totalPrice">
    <h3>Total Price: $<?php echo number_format($totalPrice, 2); ?></h3>
</div>

<form id="checkoutForm" method="post">
    <h2>Checkout</h2>
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name">
    </div>
    <div>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address">
    </div>
    <div>
        <label for="city">City:</label>
        <input type="text" id="city" name="city">
    </div>
    <div>
        <label for="postalCode">Postal Code:</label>
        <input type="text" id="postalCode" name="postalCode">
    </div>
    <button type="submit" name="checkout">Checkout</button>
</form> -->
<!-- Header -->
<div class="header">
        <h1>Your Cart</h1>
    </div>

    <div class="container">
        <!-- Cart Section -->
        <div id="cartItems"></div>

        <!-- Total Price -->
        <div class="cart-summary">
            <h2>Total Price: $<span id="totalPrice">0.00</span></h2>
        </div>

        <!-- Checkout Form -->
        <div class="checkout-form">
            <h2>Checkout</h2>
            <form id="checkoutForm">
                <input type="text" id="name" placeholder="Your Name" required>
                <input type="text" id="address" placeholder="Your Address" required>
                <input type="text" id="city" placeholder="City" required>
                <input type="text" id="postalCode" placeholder="Postal Code" required>
                <button type="submit">Complete Checkout</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Book Shop. All rights reserved.</p>
    </footer>

</body>
</html>
