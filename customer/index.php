<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/index.php"); // Redirect to login if not logged in
    exit();
}

include "../db_connect.php";

// Get only 5 products from the database
$sql = "SELECT p.id, p.name, p.price, i.img_url FROM products p JOIN product_images i ON p.id = i.product_id LIMIT 5";
$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'name'=> $row['name'],
            'price'=> $row['price'],
            'image'=> $row['img_url'],
        ];
    }
} else {
    echo "0 results";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <!-- Include the header -->
    <div id="header-container">
        <?php include "header.html"; ?>
    </div>

    <div class="main-container">
        <!-- Product List Section -->
        <div class="products-container">
            <?php foreach ($products as $product): ?>
            <div class="product">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                <h3><?php echo $product['name']; ?></h3>
                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                <p class="rating">★★★★☆</p>
                <form action="add_to_cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                    <button type="submit" class="buy-button">Add to Cart</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 DealKade Book Shop.</p>
    </footer>

    <!-- <script>
        // Smooth scroll animation for the products
        const productsContainer = document.querySelector('.products-container');
        let isScrolling = false;
        let scrollAmount = 0;
        let maxScrollLeft = productsContainer.scrollWidth - productsContainer.clientWidth;

        function smoothScroll() {
            if (!isScrolling) return;
            
            scrollAmount += 1;  // Adjust scroll speed as needed
            
            if (scrollAmount >= maxScrollLeft) {
                isScrolling = false;
            } else {
                productsContainer.scrollLeft = scrollAmount;
                requestAnimationFrame(smoothScroll);  // Smooth animation frame
            }
        }

        // Start the scrolling when the page loads
        window.onload = function() {
            isScrolling = true;
            smoothScroll();
        };
    </script> -->
</body>
</html>


