<?php
require 'db_connect.php';
require 'functions.php';

try {
    $query = "SELECT ISBN AS isbn, Author AS author, Title AS title, Price AS price FROM books";

    // If search is performed
    if (isset($_GET['searchTerm'], $_GET['type']) && !empty(trim($_GET['searchTerm']))) {
        $searchTerm = "%" . trim($_GET['searchTerm']) . "%";
        $type = $_GET['type'];

        $allowedTypes = ['author', 'title', 'isbn'];
        if (in_array($type, $allowedTypes)) {
            $query .= " WHERE $type LIKE ?";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new Exception("System error: Could not prepare search query.");
            }
            $stmt->bind_param("s", $searchTerm);
        } else {
            // fallback: invalid type
            $stmt = $conn->prepare("SELECT ISBN AS isbn, Author AS author, Title AS title, Price AS price FROM books");
            if (!$stmt) {
                throw new Exception("System error: Could not prepare query.");
            }
        }
    } else {
        // No search, fetch all
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("System error: Could not prepare query.");
        }
    }

    if (!$stmt->execute()) {
        throw new Exception("Failed to fetch books. Please try again.");
    }

    $result = $stmt->get_result();
} catch (Exception $e) {
    displayError($e->getMessage(), "search.php");
    exit;
}
?>
