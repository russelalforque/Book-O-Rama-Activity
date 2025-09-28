<?php
include 'db_connect.php';
require 'functions.php'; // for displayError / displaySuccess

try {
    $query = "SELECT ISBN AS isbn, Author AS author, Title AS title, Price AS price FROM books";

    // If search is performed
    if (isset($_GET['searchTerm'], $_GET['type']) && !empty($_GET['searchTerm'])) {
        $searchTerm = "%" . trim($_GET['searchTerm']) . "%";
        $type = strtolower(trim($_GET['type']));

        $allowedTypes = ['author', 'title', 'isbn'];
        if (in_array($type, $allowedTypes)) {
            $query .= " WHERE $type LIKE ?";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Failed to prepare search query.");
            }
            $stmt->bind_param("s", $searchTerm);
        } else {
            // fallback: invalid type
            $stmt = $conn->prepare("SELECT ISBN AS isbn, Author AS author, Title AS title, Price AS price FROM books");
            if (!$stmt) {
                throw new Exception("Failed to prepare default query.");
            }
        }
    } else {
        // No search, fetch all
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare book catalog query.");
        }
    }

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute query.");
    }

    $result = $stmt->get_result();
} catch (Exception $e) {
    displayError($e->getMessage(), "search.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Book-O-Rama Catalog</title>
</head>

<body>
    <div class="container">

        <h1>Book-O-Rama Catalog Search</h1>

        <!-- Search Form -->
        <form action="search.php" method="get">
            <label for="type">Choose Search Type:</label>
            <select id="type" name="type" required>
                <option value="author">Author</option>
                <option value="title">Title</option>
                <option value="isbn">ISBN</option>
            </select>

            <label for="searchTerm">Enter Search Term:</label>
            <input type="text" id="searchTerm" name="searchTerm" required>

            <button type="submit">Search</button>
        </form>

        <br>
        <hr><br>

        <!-- Show all books -->
        <div class="results">
            <h2>Book Results</h2>
            <div class="results-actions">
                <form action="" method="get">
                    <button type="submit">View All Books</button>
                </form>
                <a href="newbook.html" class="navbutton">Add Book</a>
            </div>
        </div>

        <div id="searchResults">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="result-item">
                        <div class="result-details">
                            <strong>Title:</strong> <?= htmlspecialchars($row['title']) ?><br>
                            <strong>Author:</strong> <?= htmlspecialchars($row['author']) ?><br>
                            <strong>ISBN:</strong> <?= htmlspecialchars($row['isbn']) ?><br>
                            <strong>Price:</strong> ₱<?= number_format($row['price'], 2) ?>
                        </div>

                        <div class="result-actions">
                            <!-- Edit Form -->
                            <form action="edit_price.php" method="post">
                                <input type="hidden" name="isbn" value="<?= htmlspecialchars($row['isbn']) ?>">
                                <button type="submit" class="edit-btn">Edit</button>
                            </form>

                            <!-- Delete Form -->
                            <form action="delete_book.php" method="post"
                                onsubmit="return confirm('Are you sure you want to delete this book?\n\nTitle: <?= addslashes($row['title']) ?>\nAuthor: <?= addslashes($row['author']) ?>\nISBN: <?= addslashes($row['isbn']) ?>\nPrice: ₱<?= addslashes($row['price']) ?>');">
                                <input type="hidden" name="isbn" value="<?= htmlspecialchars($row['isbn']) ?>">
                                <input type="hidden" name="author" value="<?= htmlspecialchars($row['author']) ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No books found in the catalog.</p>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>
