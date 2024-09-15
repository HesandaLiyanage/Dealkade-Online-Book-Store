<?php
// Assuming you have a database connection (adjust with your DB credentials)
$servername = "localhost";

$username = "root";
$password = "";

$dbname = "dealkade";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get search query, category, and price range from the form
$search = isset($_GET['query']) ? trim($_GET['query']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$priceRange = isset($_GET['price_range']) ? $_GET['price_range'] : 'all';

// Base SQL query
$sql = "SELECT p.*, pi.img_url FROM products p 
        LEFT JOIN product_images pi ON p.id = pi.product_id
        JOIN categories c ON p.category_id = c.id 
        WHERE 1=1"; // 1=1 allows us to append more conditions dynamically

$params = [];

// Add search query filter
if (!empty($search)) {
    $sql .= " AND p.name LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

// Add category filter if not "all"
if ($category !== 'all') {
    $sql .= " AND c.name = :category";
    $params[':category'] = $category;
}

// Add price range filter if not "all"
if ($priceRange !== 'all') {
    $priceBounds = explode('-', $priceRange);
    if (count($priceBounds) === 2) {
        $minPrice = (float)$priceBounds[0];
        $maxPrice = (float)$priceBounds[1];
        $sql .= " AND p.price BETWEEN :minPrice AND :maxPrice";
        $params[':minPrice'] = $minPrice;
        $params[':maxPrice'] = $maxPrice;
    }
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    /* Basic reset */
body, h2, h3, p {
    margin: 0;
    padding: 0;
}

/* Container for search results */
.search-results-container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

/* Heading */
h2 {
    font-size: 2em;
    margin-bottom: 20px;
    color: #333;
}

/* Book list layout */
.book-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

/* Individual book item */
.book-item {
    flex: 1 1 calc(25% - 20px); /* Adjusts the number of items per row */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    background-color: #fff;
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.book-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

/* Book link */
.book-link {
    text-decoration: none;
    color: #333;
}

/* Book image */
.book-image {
    width: 100%;
    height: auto;
    display: block;
}

/* Book title */
.book-title {
    font-size: 1.25em;
    margin: 15px 0;
}

/* Price styling */
.price {
    font-size: 1.1em;
    color: #e74c3c;
    margin-bottom: 10px;
}

/* Rating styling */
.rating {
    font-size: 1em;
    color: #f1c40f;
}

/* No results message */
p {
    font-size: 1.1em;
    color: #555;
}
</style>
<body>
    <div class="search-results-container">
        <h2>Search Results:</h2>
        <?php if (count($books) > 0): ?>
            <div class="book-list">
                <?php foreach ($books as $book): ?>
                    <div class="book-item">
                        <a href="book-details.php?id=<?= $book['id'] ?>">
                            <img src="<?= $book['img_url'] ?: 'default-image.png' ?>" alt="<?= htmlspecialchars($book['name']) ?>">
                            <h3><?= htmlspecialchars($book['name']) ?></h3>
                            <p class="price">$<?= number_format($book['price'], 2) ?></p>
                            <p class="rating">
                                <?= isset($book['rating']) ? str_repeat('★', $book['rating']) . str_repeat('☆', 5 - $book['rating']) : 'No rating available' ?>
                            </p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No books found matching your criteria.</p>
        <?php endif; ?>
    </div>
</body>
</html>