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
    <title>Book Shop - Amazon Style</title>
    <link rel="stylesheet" href="styles.css">
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
                <input type="text" placeholder="Search books..." class="search-bar" name='query'>
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

        <!-- Product Carousel Section -->
        <div class="carousel-container">
            <div class="carousel">
                <div class="product-card">
                    <a href="book-details.php?id=1">
                    <img src="https://via.placeholder.com/150x200" alt="Book 1">
                    <h3>Book 1</h3>
                    <p class="price">$49.99</p>
                    <p class="rating">★★★★☆</p>
                    </a>
                    <button>Add to Cart</button>
                </div>
                <div class="product-card">
                    <a href="book-details.php?id=2">
                    <img src="https://via.placeholder.com/150x200" alt="Book 2">
                    <h3>Book 2</h3>
                    <p class="price">$79.99</p>
                    <p class="rating">★★★★★</p>
                    </a>
                    <button>Add to Cart</button>
                </div>
                <div class="product-card">
                    <a href="book-details.php?id=3">
                    <img src="https://via.placeholder.com/150x200" alt="Book 3">
                    <h3>Book 3</h3>
                    <p class="price">$89.99</p>
                    <p class="rating">★★★☆☆</p>
                    </a>
                    <button>Add to Cart</button>
                </div>
                <div class="product-card">
                    <a href="book-details.php?id=4">
                    <img src="https://via.placeholder.com/150x200" alt="Book 4">
                    <h3>Book 4</h3>
                    <p class="price">$59.99</p>
                    <p class="rating">★★★★☆</p>
                    </a>
                    <button>Add to Cart</button>
                </div>
                <div class="product-card">
                    <a href="book-details.php?id=5">
                    <img src="https://via.placeholder.com/150x200" alt="Book 4">
                    <h3>Book 5</h3>
                    <p class="price">$69.69</p>
                    <p class="rating">★★★★☆</p>
                    </a>
                    <button>Add to Cart</button>
                </div>
                <div class="product-card">
                    <a href="book-details.php?id=6">
                    <img src="https://via.placeholder.com/150x200" alt="Book 4">
                    <h3>Book 6</h3>
                    <p class="price">$129.99</p>
                    <p class="rating">★★★★☆</p>
                    </a>
                    <button>Add to Cart</button>
                </div>
                <div class="product-card">
                    <a href="book-details.php?id=7">
                    <img src="https://via.placeholder.com/150x200" alt="Book 4">
                    <h3>Book 7</h3>
                    <p class="price">$29.99</p>
                    <p class="rating">★★★★☆</p>
                    </a>
                    <button>Add to Cart</button>
                </div>
                <div class="product-card">
                    <a href="book-details.php?id=8">
                    <img src="https://via.placeholder.com/150x200" alt="Book 4">
                    <h3>Book 8</h3>
                    <p class="price">$229.99</p>
                    <p class="rating">★★★★☆</p>
                    </a>
                    <button>Add to Cart</button>
                </div>
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

        // Carousel Auto Slide Functionality
        let currentSlide = 0;
        const carousel = document.querySelector('.carousel');
        const totalSlides = document.querySelectorAll('.product-card').length;
        const slideWidth = document.querySelector('.product-card').clientWidth;

        function autoSlide() {
            currentSlide++;
            if (currentSlide >= totalSlides) {
                currentSlide = 0;
            }
            carousel.style.transition = 'transform 0.5s ease-in-out';
            carousel.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
        }

        // Set up interval for automatic sliding every 3 seconds
        setInterval(autoSlide, 3000);
    </script>
</body>
</html>
