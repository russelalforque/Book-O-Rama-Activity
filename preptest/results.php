<?php
require 'functions.php'; // for displayError / displaySuccess

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "book_o_rama";

try {
    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Unable to connect to the database. Please try again later.");
    }

    // Get inputs
    $type = strtolower(trim($_GET['type'] ?? ''));
    $searchTerm = trim($_GET['searchTerm'] ?? '');

    // Validate search type
    $allowedTypes = ['isbn', 'author', 'title'];
    if (!in_array($type, $allowedTypes)) {
        throw new Exception("Invalid search type. Please choose ISBN, Author, or Title.");
    }

    // Validation: Search term
    if ($searchTerm === '') {
        throw new Exception("Please enter a search term.");
    }
    if (strlen($searchTerm) < 2) {
        throw new Exception("Search term must be at least 2 characters long.");
    }

    // Build query
    $sql = "SELECT ISBN AS isbn, Author AS author, Title AS title, Price AS price 
            FROM books 
            WHERE $type LIKE ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("We couldn’t prepare the search. Please try again later.");
    }

    // Sanitization for search term
    $like = "%" . $searchTerm . "%";
    $stmt->bind_param("s", $like);

    if (!$stmt->execute()) {
        throw new Exception("We couldn’t perform the search. Please try again later.");
    }

    $result = $stmt->get_result();
} catch (Exception $e) {
    displayError($e->getMessage(), "search.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Book-O-Rama Search Results</title>
</head>
<body>
    <div class="container">
        <h1>Book-O-Rama Search Results</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="result-item">
                    <strong>Title:</strong> <?= htmlspecialchars($row['title']) ?><br>
                    <strong>Author:</strong> <?= htmlspecialchars($row['author']) ?><br>
                    <strong>ISBN:</strong> <?= htmlspecialchars($row['isbn']) ?><br>
                    <strong>Price:</strong> ₱<?= number_format($row['price'], 2) ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No results found for '<b><?= htmlspecialchars($searchTerm) ?></b>' in <?= ucfirst($type) ?>.</p>
        <?php endif; ?>
        <a href="search.html">Back to Search</a>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
