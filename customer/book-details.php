<?php
// Connect to the database
$servername = "localhost";

$username = "root";
$password = "";

$dbname = "dealkade";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product (book) ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Initialize variables
$title = $author = $price = $description = $image_url = $category = "Not available.";
$stock = 0;
$reviews = [];

if ($product_id > 0) {
    // Fetch product details, category, and image
    $sql = "SELECT products.*, book_categories.category_name, book_images.image_url
            FROM products
            LEFT JOIN book_categories ON products.category_id = book_categories.id
            LEFT JOIN book_images ON products.id = book_images.book_id
            WHERE products.id = $product_id";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch product details and store them in variables
        $product = $result->fetch_assoc();
        $title = $product['title'];
        $author = $product['author'];
        $price = "$" . number_format($product['price'], 2);  // Format price
        $stock = $product['stock'];
        $description = $product['description'];
        $image_url = $product['image_url'];
        $category = $product['category_name'];
    }

    // Fetch reviews for the product
    $sql_reviews = "SELECT rating, comment FROM reviews WHERE product_id = $product_id";
    $result_reviews = $conn->query($sql_reviews);

    if ($result_reviews->num_rows > 0) {
        while ($review = $result_reviews->fetch_assoc()) {
            $reviews[] = $review;  // Store each review in the array
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your previous project's CSS -->
</head>
<body>

    <div class="product-details-container">
        <!-- Product Image -->
        <div class="product-image">
            <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($title); ?>" />
        </div>

        <!-- Product Information -->
        <div class="product-info">
            <h1><?php echo htmlspecialchars($title); ?></h1>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($author); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($category); ?></p>
            <p><strong>Price:</strong> <?php echo htmlspecialchars($price); ?></p>
            <p><strong>Stock Available:</strong> <?php echo htmlspecialchars($stock); ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($description)); ?></p>
        </div>

        <!-- Add to Cart and Buy Now -->
        <div class="product-actions">
            <form action="cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $stock; ?>" required>
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
            <form action="buy.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" name="buy_now">Buy Now</button>
            </form>
        </div>

        <!-- Reviews Section -->
        <div class="product-reviews">
            <h2>Customer Reviews</h2>
            <?php if (!empty($reviews)): ?>
                <ul>
                    <?php foreach ($reviews as $review): ?>
                        <li>
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

</body>
</html>
