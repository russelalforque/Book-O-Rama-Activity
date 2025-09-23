<?php

require 'functions.php';

$author = trim($_POST['author']);
$isbn = trim($_POST['isbn']);

$bookData = [$isbn, $author];

if (!isValid($bookData)) {
    displayError("Some fields are empty! Please try again.", "search.php");
    exit;
}

require 'db_connect.php';

// Verify that the record exists and author matches
$stmt = $conn->prepare("SELECT * FROM books WHERE ISBN = ? AND Author = ?");
$stmt->bind_param("ss", $isbn, $author);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    displayError("No book found with that ISBN and author!", "search.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM books WHERE ISBN = ? AND Author = ?");
$stmt->bind_param("ss", $isbn, $author);

if ($stmt->execute()) {
    displaySuccess("Book deleted successfully!", "search.php");
} else {
    displayError("Failed to delete the book. Please try again.", "search.php");
}

$stmt->close();
$conn->close();
?>