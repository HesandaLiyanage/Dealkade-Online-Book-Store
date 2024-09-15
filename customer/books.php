<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/index.php"); // Redirect to login if not logged in
    exit();
}

// User or admin-specific content goes here

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Page</title>
    <style>
    /* General Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
}

/* Header Styles */
header {
    background-color: #333;
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo h1 {
    font-size: 24px;
}

nav ul {
    list-style: none;
}

nav ul li {
    display: inline;
    margin-right: 20px;
}

nav ul li a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}

nav ul li a:hover {
    text-decoration: underline;
}

/* Search Form */
.search-form {
    display: flex;
}

.search-form input[type="text"] {
    padding: 8px;
    border: none;
    border-radius: 4px 0 0 4px;
    width: 200px;
}

.search-form button {
    padding: 8px 16px;
    background-color: #ff6600;
    border: none;
    color: white;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
}

.search-form button:hover {
    background-color: #ff4500;
}

/* Main Section */
main {
    padding: 20px;
    text-align: center;
}

main h2 {
    margin-bottom: 30px;
    font-size: 28px;
    color: #333;
}

/* Products Grid */
.products-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.product {
    background-color: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.product:hover {
    transform: translateY(-5px);
}

.product img {
    max-width: 100%;
    border-radius: 8px;
    margin-bottom: 10px;
}

.product h3 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #333;
}

.product p {
    font-size: 16px;
    color: #666;
}

.buy-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #ff6600;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.buy-button:hover {
    background-color: #ff4500;
}

/* Footer Styles */
footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 20px;
    margin-top: 20px;
}

footer p {
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .products-container {
        grid-template-columns: 1fr 1fr;
    }

    nav ul li {
        margin-right: 10px;
    }

    .search-form input[type="text"] {
        width: 150px;
    }
}

@media (max-width: 480px) {
    .products-container {
        grid-template-columns: 1fr;
    }

    .search-form input[type="text"] {
        width: 100px;
    }
}
</style> <!-- Assuming the CSS is in styles.css -->
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
                        <option value="novels">Novels</option>
                        <option value="scifi">Sci-Fi</option>
                        <option value="fiction">Fiction</option>
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
            <!-- Repeating Book Cards -->
            <div class="product">
                <img src="https://via.placeholder.com/150x200" alt="Book 1">
                <h3>Book 1</h3>
                <p class="price">$49.99</p>
                <p class="rating">★★★★☆</p>
                <button class="buy-button">Add to Cart</button>
            </div>

            <div class="product">
                <img src="https://via.placeholder.com/150x200" alt="Book 2">
                <h3>Book 2</h3>
                <p class="price">$79.99</p>
                <p class="rating">★★★★★</p>
                <button class="buy-button">Add to Cart</button>
            </div>

            <div class="product">
                <img src="https://via.placeholder.com/150x200" alt="Book 3">
                <h3>Book 3</h3>
                <p class="price">$89.99</p>
                <p class="rating">★★★☆☆</p>
                <button class="buy-button">Add to Cart</button>
            </div>

            <div class="product">
                <img src="https://via.placeholder.com/150x200" alt="Book 4">
                <h3>Book 4</h3>
                <p class="price">$59.99</p>
                <p class="rating">★★★★☆</p>
                <button class="buy-button">Add to Cart</button>
            </div>

            <!-- Additional books -->
            <div class="product">
                <img src="https://via.placeholder.com/150x200" alt="Book 5">
                <h3>Book 5</h3>
                <p class="price">$69.69</p>
                <p class="rating">★★★★☆</p>
                <button class="buy-button">Add to Cart</button>
            </div>

            <div class="product">
                <img src="https://via.placeholder.com/150x200" alt="Book 6">
                <h3>Book 6</h3>
                <p class="price">$129.99</p>
                <p class="rating">★★★★☆</p>
                <button class="buy-button">Add to Cart</button>
            </div>

            <div class="product">
                <img src="https://via.placeholder.com/150x200" alt="Book 7">
                <h3>Book 7</h3>
                <p class="price">$29.99</p>
                <p class="rating">★★★★☆</p>
                <button class="buy-button">Add to Cart</button>
            </div>

            <div class="product">
                <img src="https://via.placeholder.com/150x200" alt="Book 8">
                <h3>Book 8</h3>
                <p class="price">$229.99</p>
                <p class="rating">★★★★☆</p>
                <button class="buy-button">Add to Cart</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Book Shop. Powered by Hesanda.</p>
    </footer>

    <script>
        // Loading the header dynamically
        fetch("header.html")
            .then(response => response.text())
            .then(data => document.getElementById('header-container').innerHTML = data);
    </script>
</body>
</html>