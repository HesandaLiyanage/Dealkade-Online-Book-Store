<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['role']) === 'admin') {
    // header("Location: ../Login/index.php"); // Redirect to login if not logged in
    echo "You aren't an admin!!!";
    // header("Location: ../Login/index.php");
    exit();
}

// Include database connection
include "../db_connect.php";

// Initialize variables for CRUD operations
$products = [];
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle CRUD operations here (e.g., Add, Edit, Delete products)

// Fetch all products for listing
$sql = "SELECT products.id, products.name AS product_name, products.price, products.stock_quantity, 
               categories.name AS category_name
        FROM products
        LEFT JOIN categories ON products.category_id = categories.id";
$result = $conn->query($sql);

if ($result === false) {
    die("Error in query: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) {
        $products[] = $product;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
    <style>
        /* General Styles */
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

        /* Product Table */
        .product-table {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #FF6F61;
            color: white;
        }

        td a {
            color: #FF6F61;
            text-decoration: none;
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

        .btn-add-new {
            display: block;
            margin: 20px 0;
            text-align: right;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard - Manage Products</h1>
    </header>

    <!-- Product List -->
    <div class="product-table">
        <div class="btn-add-new">
            <a href="add_product.php" class="btn btn-add">Add New Product</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                        <td><?php echo htmlspecialchars("$" . number_format($product['price'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
