<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

include "../db_connect.php";

$sql = "SELECT p.id , p.name , p.price , i.img_url FROM products p JOIN product_images i ON p.id = i.product_id";
$result = $conn->query($sql);


$products = [];

if ($result -> num_rows > 0){

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
    <title>Books Page</title>
    <link rel="stylesheet" href="books.css">
</head>
<body>
    <!-- Include the header -->
    <div id="header-container">
    <?php
        include "header.html"
        ?>
    </div>

    <div class="main-container">
        <!-- Search and Filter Section -->
        <div class="search-filter-container">
            <form action="search.php" method="GET">
                <input type="text" placeholder="Search books..." class="search-bar" name="query">
                <div class="filters">
                    <label for="category">Category:</label>
                    <select id="category" name='category'>
                        <option value="all">All</option>
                        <option value="Fiction">Fiction</option>
                        <option value="Non-Fiction">Non-Fiction</option>
                        <option value="Children\'s Books">Children's Books</option>
                        <option value="Science">Science</option>
                        <option value="Self-Help">Self-Help</option>
                    </select>

                    <label for="price-range">Price Range:</label>
                    <select id="price-range" name='price_range'>
                        <option value="all">All</option>
                        <option value="0-50">$0 - $50</option>
                        <option value="50-100">$50 - $100</option>
                        <option value="100-200">$100 - $200</option>
                    </select>

                    <input type="submit" class="filter-button" value="Apply filters"/>
                </div>
            </form>
        </div>

        <!-- Product List Section -->
        <div class="products-container">
    <?php foreach ($products as $product): ?>
    <div class="product">
        <a href="book-details.php?id=<?php echo $product['id']; ?>" style="text-decoration: none; color: inherit;">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            <h3><?php echo $product['name']; ?></h3>
            <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
            <p class="rating">★★★★☆</p>
        </a>
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

    <script>
        // Loading the header dynamically
        fetch("header.html")
            .then(response => response.text())
            .then(data => document.getElementById('header-container').innerHTML = data);
    </script>
</body>
</html>