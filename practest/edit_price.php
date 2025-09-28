<?php
require 'db_connect.php';
require 'functions.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['isbn'], $_POST['price'])) {
        // --- SANITIZATION ---
        $isbn = trim($_POST['isbn']);   // remove whitespace
        $price = trim($_POST['price']); // remove whitespace

        // --- VALIDATION ---
        // ISBN: must not be empty, must contain digits only
        if ($isbn === '' || !preg_match('/^[0-9]+$/', $isbn)) {
            throw new Exception("Invalid ISBN! ISBN should only contain digits.");
        }

        // Price: must not be empty, must be numeric, must be > 0
        if ($price === '' || !is_numeric($price) || (float)$price <= 0) {
            throw new Exception("Invalid Price! Please enter a number greater than zero.");
        }

        // --- FINAL SANITIZATION for DB ---
        $isbn = htmlspecialchars($isbn, ENT_QUOTES, 'UTF-8'); 
        $price = number_format((float)$price, 2, '.', '');    

        // --- QUERY ---
        $stmt = $conn->prepare("UPDATE books SET Price = ? WHERE ISBN = ?");
        if (!$stmt) {
            throw new Exception("System error while preparing query.");
        }

        $stmt->bind_param("ds", $price, $isbn);

        if ($stmt->execute()) {
            displaySuccess("Price updated successfully!", "search.php");
        } else {
            throw new Exception("Failed to update price. Please try again.");
        }
        exit;
    }

    // Handle case where ISBN not provided
    if (!isset($_POST['isbn']) || $_POST['isbn'] === '') {
        throw new Exception("No ISBN provided!");
    }

    // --- SANITIZATION ---
    $isbn = trim($_POST['isbn']);

    // --- VALIDATION ---
    if ($isbn === '' || !preg_match('/^[0-9]+$/', $isbn)) {
        throw new Exception("Invalid ISBN format! ISBN should only contain digits.");
    }

    // --- FINAL SANITIZATION for DB ---
    $isbn = htmlspecialchars($isbn, ENT_QUOTES, 'UTF-8');

    // Fetch book details
    $stmt = $conn->prepare("SELECT ISBN, Title, Author, Price FROM books WHERE ISBN = ?");
    if (!$stmt) {
        throw new Exception("System error while preparing query.");
    }

    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Book not found!");
    }

    $book = $result->fetch_assoc();

} catch (Exception $e) {
    displayError($e->getMessage(), "search.php");
    exit;
}
?>
