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
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$priceRange = isset($_GET['price_range']) ? $_GET['price_range'] : 'all';

// Construct SQL query
$sql = "SELECT p. * FROM products p JOIN categories c ON p.category_id = c.id WHERE p.name LIKE :search";
$params = [':search' => '%' . $search . '%'];

// Add category filter if not "all"
if ($category !== 'all') {
    $sql .= " AND c.name = :category";
    $params[':category'] = $category;
}

// Add price range filter if not "all"
if ($priceRange !== 'all') {
    $priceBounds = explode('-', $priceRange);
    $minPrice = (float)$priceBounds[0];
    $maxPrice = (float)$priceBounds[1];
    $sql .= " AND price BETWEEN :minPrice AND :maxPrice";
    $params[':minPrice'] = $minPrice;
    $params[':maxPrice'] = $maxPrice;
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
<body>
    <div class="search-results-container">
        <h2>Search Results:</h2>
        <?php if (count($books) > 0): ?>
            <div class="book-list">
                <?php foreach ($books as $book): ?>
                    <div class="book-item">
                        <a href="book-details.php?id=<?= $book['id'] ?>">
                            <img src="<?= $book['image_url'] ?>" alt="<?= $book['title'] ?>">
                            <h3><?= $book['title'] ?></h3>
                            <p class="price">$<?= number_format($book['price'], 2) ?></p>
                            <p class="rating"><?= str_repeat('★', $book['rating']) . str_repeat('☆', 5 - $book['rating']) ?></p>
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