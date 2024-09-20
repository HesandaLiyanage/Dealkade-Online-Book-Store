<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

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
    <link rel="stylesheet" href="../css/book-details.css">
</head>
<body>
    <header>
        <?php
        include "header.html"
        ?>
    </header>
    <main>
        <div class="product-details-container">
            <!-- Product Image -->
            <div class="product-image">
                <img src="<?php echo $image_url; ?>" alt="<?php echo $product_name; ?>" />
            </div>

            <!-- Product Information -->
            <div class="product-info">
                <h1><?php echo $product_name; ?></h1>
                <p><strong>Category:</strong> <?php echo $category_name; ?></p>
                <p><strong>Price:</strong> <?php echo $price; ?></p>
                <p><strong>Stock Available:</strong> <?php echo $stock; ?></p>
                <p><strong>Description:</strong> <?php echo nl2br($description); ?></p>

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
                    </form>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="product-reviews">
                <h2>Customer Reviews</h2>
                <?php if (!empty($reviews)): ?>
                    <ul>
                        <?php foreach ($reviews as $review): ?>
                            <li class="review">
                                <p><strong>Rating:</strong> <?php echo $review['rating']; ?>/5</p>
                                <p><strong>Comment:</strong> <?php echo $review['comment']; ?></p>
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
