<?php
session_start();
require '../db_connect.php'; // Include your DB connection

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];
$totalPrice = 0;

function getCart($conn, $user_id) {
    $query = "SELECT * FROM cart WHERE user_id = $user_id";
    $result = $conn->query($query);
    $cart = $result->fetch_assoc();

    if (!$cart) {
        $query = "INSERT INTO cart (user_id, created_at, updated_at) VALUES ($user_id, NOW(), NOW())";
        $conn->query($query);
        $cart_id = $conn->insert_id;
    } else {
        $cart_id = $cart['id'];
    }

    return $cart_id;
}

// Function to get cart items
function getCartItems($conn, $cart_id) {
    $query = "SELECT ci.id as cart_item_id, p.name, p.price, ci.quantity, ci.product_id
              FROM cart_items ci
              JOIN products p ON ci.product_id = p.id
              WHERE ci.cart_id = $cart_id";
    $result = $conn->query($query);
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
        $query = "UPDATE cart_items SET quantity = $quantity, updated_at = NOW() WHERE cart_id = $cart_id AND product_id = $product_id";
        $conn->query($query);
    }

    if (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];
        $query = "DELETE FROM cart_items WHERE cart_id = $cart_id AND product_id = $product_id";
        $conn->query($query);
    }

    if (isset($_POST['checkout'])) {
        $totalPrice = 0;
        $cartItems = getCartItems($conn, $cart_id);

        foreach ($cartItems as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $status = "shipped";
        $query = "INSERT INTO orders (user_id, total_amount, status, created_at, updated_at)
                  VALUES ($user_id, $totalPrice, '$status', NOW(), NOW())";
        $conn->query($query);
        $order_id = $conn->insert_id;

        foreach ($cartItems as $item) {
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase, created_at, updated_at)
                      VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']}, NOW(), NOW())";
            $conn->query($query);
        }

        $query = "DELETE FROM cart_items WHERE cart_id = $cart_id";
        $conn->query($query);

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
    <link rel="stylesheet" href="../css/cart.css">
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
