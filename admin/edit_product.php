<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) === 'admin') {
    echo "You aren't an admin!!!";
    exit();
}

include "../db_connect.php";

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;

// Fetch the product data based on the ID
if ($product_id) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }
    $stmt->close();
}

// Handle form submission for updating the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];

    $update_sql = "UPDATE products SET name = ?, price = ?, stock_quantity = ?, category_id = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sdiii", $name, $price, $stock_quantity, $category_id, $product_id);
    $update_stmt->execute();
    $update_stmt->close();

    header("Location: products.php"); // Redirect back to the product management page
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background-color: #f4f4f4;
    }

/* Header */
    header {
        background-color: #333;
        color: white;
        padding: 10px 20px;
    }

    header h1 {
        margin: 0;
    }

/* Product Form */
    .product-form {
        width: 80%;
        margin: 20px auto;
        background-color: white;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .product-form h1 {
        margin-top: 0;
    }

    .product-form label {
        display: block;
        margin: 10px 0 5px;
    }

    .product-form input {
        width: calc(100% - 22px);
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .product-form button {
        padding: 10px 15px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        font-size: 14px;
        background-color: #4CAF50;
        color: white;
    }

    .product-form button:hover {
        opacity: 0.9;
    }

/* Buttons */
    .btn {
        padding: 8px 15px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        font-size: 14px;
        margin-right: 10px;
    }

    .btn-add {
        background-color: #4CAF50;
        color: white;
    }

    .btn-edit {
        background-color: #FFCC00;
        color: black;
    }

    .btn-delete {
        background-color: #FF6F61;
        color: white;
    }

    .btn:hover {
        opacity: 0.9;
    }

    </style>
</head>
<body>
    <h1>Edit Product</h1>
    <?php if ($product): ?>
        <form method="POST" action="">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>

            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br>

            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" id="stock_quantity" name="stock_quantity" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required><br>

            <!-- <label for="category_id">Category:</label>
            <input type="number" id="category_id" name="category_id" value="<?php echo htmlspecialchars($product['category_id']); ?>" required><br> -->

            <button type="submit">Update Product</button>
        </form>
    <?php else: ?>
        <p>Product not found.</p>
    <?php endif; ?>
</body>
</html>
