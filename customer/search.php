<?php
// Assuming you have a database connection (adjust with your DB credentials)
include "../db_connect.php"

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

$result = $conn->query($sql);
$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<link rel="stylesheet" href="../css/search.css">
<body>
    <div class="search-results-container">
        <h2>Search Results:</h2>
        <?php if (count($books) > 0): ?>
            <div class="book-list">
                <?php foreach ($books as $book): ?>
                    <div class="book-item">
                        <a href="book-details.php?id=<?= $book['id'] ?>">
                            <img src="<?= $book['img_url'] ?: 'default-image.png' ?>" alt="<?= $book['name'] ?>">
                            <h3><?= $book['name'] ?></h3>
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