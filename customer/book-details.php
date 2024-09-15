<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/index.php"); // Redirect to login if not logged in
    exit();
}

// // Connect to the database
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "dealkade";

// $conn = new mysqli($servername, $username, $password, $dbname);

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
include "../db_connect.php";

// Get the product (book) ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Initialize variables
$product_name = $author = $price = $description = $image_url = $category_name = "Not available.";
$stock = 0;
$reviews = [];

if ($product_id > 0) {
    // Fetch product details, category, and image
    $sql = "SELECT products.name AS product_name, products.price, products.stock_quantity, 
                   products.description, categories.name AS category_name, product_images.img_url
            FROM products
            LEFT JOIN categories ON products.category_id = categories.id
            LEFT JOIN product_images ON products.id = product_images.product_id
            WHERE products.id = $product_id";
    
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error in query: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $product_name = $product['product_name'];
        $price = "$" . number_format($product['price'], 2);
        $stock = $product['stock_quantity'];
        $description = $product['description'];
        $image_url = $product['img_url'];
        $category_name = $product['category_name'];
    }

    // Fetch reviews for the product
    $sql_reviews = "SELECT rating, comment FROM reviews WHERE product_id = $product_id";
    $result_reviews = $conn->query($sql_reviews);

    if ($result_reviews->num_rows > 0) {
        while ($review = $result_reviews->fetch_assoc()) {
            $reviews[] = $review;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product_name); ?></title>
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

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

nav ul {
    list-style-type: none;
}

nav ul li {
    display: inline;
    margin-right: 15px;
}

nav ul li a {
    color: white;
    text-decoration: none;
}

/* Main Product Page Layout */
main {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.product-container {
    display: flex;
    background-color: white;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 80%;
    max-width: 1200px;
}

.product-image {
    flex: 1;
    padding-right: 20px;
}

.product-image img {
    max-width: 100%;
    height: auto;
    display: block;
}

.product-details {
    flex: 2;
}

.product-price {
    font-size: 24px;
    font-weight: bold;
    color: #FF6F61;
    margin: 10px 0;
}

.product-description {
    margin: 15px 0;
}

/* Colorful Buttons */
.button-container {
    margin-top: 20px;
}

.btn {
    padding: 12px 20px;
    font-size: 16px;
    border: none;
    cursor: pointer;
    margin-right: 10px;
    border-radius: 5px;
}

.buy-now {
    background-color: #FF6F61;
    color: white;
}

.add-to-cart {
    background-color: #4CAF50;
    color: white;
}

.wishlist {
    background-color: #FFCC00;
    color: black;
}

.btn:hover {
    opacity: 0.9;
}
</style>
</head>
<body>

    <!-- Your existing header -->
    <header>
        <!-- <div class="header-content">
            <h1>My Bookshop</h1>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Products</a></li>
                    <li><a href="#">Cart</a></li>
                </ul>
            </nav>
        </div> -->
        <?php
        include "header.html"
        ?>
    </header>

    <!-- Fullscreen Product Page Content -->
    <main>
        <div class="product-details-container">
            <!-- Product Image -->
            <div class="product-image">
                <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($product_name); ?>" />
            </div>

            <!-- Product Information -->
            <div class="product-info">
                <h1><?php echo htmlspecialchars($product_name); ?></h1>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($category_name); ?></p>
                <p><strong>Price:</strong> <?php echo htmlspecialchars($price); ?></p>
                <p><strong>Stock Available:</strong> <?php echo htmlspecialchars($stock); ?></p>
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($description)); ?></p>

                <!-- Colorful Buttons -->
                <div class="button-container">
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $stock; ?>" required>
                        <button type="submit" class="btn add-to-cart">Add to Cart</button>
                    </form>
                    <form action="buy.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn buy-now">Buy Now</button>
                    </form>
                    <!-- <button class="btn wishlist">Add to Wishlist</button> -->
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="product-reviews">
                <h2>Customer Reviews</h2>
                <?php if (!empty($reviews)): ?>
                    <ul>
                        <?php foreach ($reviews as $review): ?>
                            <li class="review">
                                <p><strong>Rating:</strong> <?php echo htmlspecialchars($review['rating']); ?>/5</p>
                                <p><strong>Comment:</strong> <?php echo htmlspecialchars($review['comment']); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No reviews yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

</body>
</html>
