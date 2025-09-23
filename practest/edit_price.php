<?php
require 'db_connect.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['isbn'], $_POST['price'])) {
    $isbn = trim($_POST['isbn']);
    $price = trim($_POST['price']);

    if (empty($isbn) || !is_numeric($price)) {
        displayError("Invalid input! Please enter a valid price.", "search.php");
        exit;
    }

    $stmt = $conn->prepare("UPDATE books SET Price = ? WHERE ISBN = ?");
    $stmt->bind_param("ds", $price, $isbn);

    if ($stmt->execute()) {
        displaySuccess("Price updated successfully!", "search.php");
    } else {
        displayError("Failed to update price. Please try again.", "search.php");
    }
    exit;
}

if (!isset($_POST['isbn']) || empty($_POST['isbn'])) {
    displayError("No ISBN provided!", "search.php");
    exit;
}

$isbn = trim($_POST['isbn']);

// Fetch book details
$stmt = $conn->prepare("SELECT ISBN, Title, Author, Price FROM books WHERE ISBN = ?");
$stmt->bind_param("s", $isbn);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    displayError("Book not found!", "search.php");
    exit;
}

$book = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Edit Book Price</title>
</head>
<body>
    <div class="container">
        <h1>Edit Price</h1>

        <p><strong>Title:</strong> <?= htmlspecialchars($book['Title']) ?></p>
        <p><strong>Author:</strong> <?= htmlspecialchars($book['Author']) ?></p>
        <p><strong>ISBN:</strong> <?= htmlspecialchars($book['ISBN']) ?></p>
        <p><strong>Current Price:</strong> ₱<?= number_format($book['Price'], 2) ?></p>

        <form action="edit_price.php" method="post" class="form-actions">
            <input type="hidden" name="isbn" value="<?= htmlspecialchars($book['ISBN']) ?>">

            <label for="price">New Price:</label>
            <input type="number" step="0.01" name="price" required>

            <button type="submit">Update Price</button>
            <a href="search.php" class="cancel-btn">Cancel</a>
        </form>
    </div>
</body>
</html>
