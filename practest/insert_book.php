<?php
require 'functions.php';

$isbn = trim($_POST['isbn'] ?? '');
$author = trim($_POST['author'] ?? '');
$title = trim($_POST['title'] ?? '');
$price = $_POST['price'] ?? '';
$price = is_numeric($price) ? (float) $price : '';

$bookData = [$isbn, $author, $title, $price];

if (!isValid($bookData)) {
    displayError("Some fields are empty! Please try again.", "newbook.html");
    exit;
}

require 'db_connect.php';
$stmt = $conn->prepare("INSERT INTO books (ISBN, Author, Title, Price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssd", $isbn, $author, $title, $price);

if ($stmt->execute()) {
    displaySuccess("Book Inserted Successfully!","newbook.html");
} else {
    displayError("Database error: " . htmlspecialchars($stmt->error),  "newbook.html");
}
$stmt->close();
$conn->close();
?>