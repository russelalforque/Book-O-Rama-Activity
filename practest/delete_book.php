<?php
require 'functions.php';

try {
    // --- Input sanitization & validation ---
    $isbn = trim($_POST['isbn'] ?? '');
    $author = trim($_POST['author'] ?? '');

    // Check required fields
    if ($isbn === '' || $author === '') {
        throw new Exception("Both ISBN and Author fields are required.");
    }

    // ISBN: must be digits only
    if (!preg_match('/^[0-9]+$/', $isbn)) {
        throw new Exception("ISBN should only contain numbers (no spaces, letters, or symbols).");
    }

    // Author: allow only letters, spaces, dots, apostrophes, and hyphens
    $author = preg_replace("/[^a-zA-Z\s\.\'\-]/", "", $author);
    if ($author === '') {
        throw new Exception("Author name is invalid. Please use letters and basic punctuation only.");
    }

    require 'db_connect.php';

    // --- Verify record existence ---
    $stmt = $conn->prepare("SELECT * FROM books WHERE ISBN = ? AND Author = ?");
    $stmt->bind_param("ss", $isbn, $author);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("No book found with that ISBN and Author.");
    }

    // --- Delete record ---
    $stmt = $conn->prepare("DELETE FROM books WHERE ISBN = ? AND Author = ?");
    $stmt->bind_param("ss", $isbn, $author);

    if ($stmt->execute()) {
        displaySuccess("Book deleted successfully!", "search.php");
    } else {
        throw new Exception("Failed to delete the book. Please try again.");
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    displayError($e->getMessage(), "search.php");
}
?>
