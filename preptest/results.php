<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "book_o_rama";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get inputs
$type = $_GET['type'] ?? '';
$searchTerm = trim($_GET['searchTerm'] ?? '');

// Validate search type
$allowedTypes = ['isbn', 'author', 'title'];
if (!in_array(strtolower($type), $allowedTypes)) {
    die("<p>Invalid search type.</p>");
}

if ($searchTerm === '') {
    die("<p>Please enter a search term.</p>");
}
if (strlen($searchTerm) < 2) {
    die("<p>Search term must be at least 2 characters long.</p>");
}

$sql = "SELECT ISBN AS isbn, Author AS author, Title AS title, Price AS price 
        FROM books 
        WHERE $type LIKE ?";

$stmt = $conn->prepare($sql);
$like = "%$searchTerm%";
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();
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
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='result-item'>";
                echo "<strong>Title:</strong> " . htmlspecialchars($row['title']) . "<br>";
                echo "<strong>Author:</strong> " . htmlspecialchars($row['author']) . "<br>";
                echo "<strong>ISBN:</strong> " . htmlspecialchars($row['isbn']) . "<br>";
                echo "<strong>Price:</strong> ₱" . number_format($row['price'], 2);
                echo "</div>";
            }
        } else {
            echo "<p>No results found for '<b>" . htmlspecialchars($searchTerm) . "</b>' in " . ucfirst($type) . ".</p>";
        }
        ?>
        <a href="search.html">Back to Search</a>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
