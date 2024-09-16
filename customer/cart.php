<?php
session_start();
require '../db_connect.php'; // Include your DB connection

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/index.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];
$totalPrice = 0;

// Function to get the user's cart
function getCart($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart = $result->fetch_assoc();

    if (!$cart) {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, created_at, updated_at) VALUES (?, NOW(), NOW())");
        $stmt->bind_param('i', $user_id);
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
                            WHERE ci.cart_id = ?");
    $stmt->bind_param('i', $cart_id);
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
        $stmt = $conn->prepare("UPDATE cart_items SET quantity = ?, updated_at = NOW() WHERE cart_id = ? AND product_id = ?");
        $stmt->bind_param('iii', $quantity, $cart_id, $product_id);
        $stmt->execute();
    }

    if (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->bind_param('ii', $cart_id, $product_id);
        $stmt->execute();
    }

    if (isset($_POST['checkout'])) {
        $totalPrice = 0;
        $cartItems = getCartItems($conn, $cart_id);

        foreach ($cartItems as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $status = "shipped";
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, created_at, updated_at)
                                VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->bind_param('ids', $user_id, $totalPrice, $status);
        $stmt->execute();
        $order_id = $conn->insert_id;

        foreach ($cartItems as $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase, created_at, updated_at)
                                    VALUES (?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param('iiid', $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ?");
        $stmt->bind_param('i', $cart_id);
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
            padding: 10px;
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
            padding: 10px;
            background-color: #333;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
<div id="header-container">
    <?php
        include "header.html"
        ?>
    </div>

<div class="header">
    <h1>Your Cart</h1>
</div>

<div class="container">
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

    <div class="cart-summary">
        <p>Total Price: $<?php echo number_format($totalPrice, 2); ?></p>
    </div>

    <div class="checkout-form">
        <form method="post">
            <button type="submit" name="checkout">Checkout</button>
        </form>
    </div>
</div>

<footer>
    <p>Footer Content Here</p>
</footer>

</body>
</html>
