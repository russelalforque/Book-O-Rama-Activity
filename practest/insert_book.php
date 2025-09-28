<?php
require 'functions.php';

// Make sure mysqli throws exceptions so we can catch them
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Sanitize & validate input
    $isbn = trim($_POST['isbn'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $price = $_POST['price'] ?? '';
    $price = is_numeric($price) ? (float) $price : '';

    // Validation: required fields
    if ($isbn === '' || $author === '' || $title === '' || $price === '') {
        throw new Exception("Please complete all fields before submitting the form.");
    }

    // Validation: ISBN must be digits only
    if (!preg_match('/^[0-9]+$/', $isbn)) {
        throw new Exception("ISBN should only contain numbers. Please remove any letters, spaces, or symbols.");
    }

    // Validation: Price must be numeric and positive
    if (!is_numeric($price) || $price <= 0) {
        throw new Exception("Price should be a valid number greater than zero.");
    }


    // Sanitization
    $author = preg_replace("/[^a-zA-Z\s\.\'\-]/", "", $author);
    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

    require 'db_connect.php';

    // Insert record
    $stmt = $conn->prepare("INSERT INTO books (ISBN, Author, Title, Price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $isbn, $author, $title, $price);

    $stmt->execute();

    displaySuccess("Book added successfully!", "newbook.html");

    $stmt->close();
    $conn->close();

} catch (mysqli_sql_exception $e) {
    // Handle database errors by error code
    switch ($e->getCode()) {
        case 1062: // Duplicate ISBN
            $message = "This ISBN is already registered. Please check your list of books or use a different ISBN.";
            break;
        case 1146: // Table missing
            $message = "The system is missing some setup information. Please contact the administrator.";
            break;
        case 1054: // Column missing
            $message = "The system has a setup issue. Please contact the administrator.";
            break;
        case 1364: // Missing field
            $message = "One of the required details is missing. Please review your input and try again.";
            break;
        case 1451: // Foreign key constraint
            $message = "This book cannot be changed because it is still linked to other records. Please check related entries first.";
            break;
        case 1452: // Foreign key issue on insert
            $message = "The information you entered refers to something that doesn’t exist yet. Please review and try again.";
            break;
        default:
            $message = "Something went wrong while saving the book. Please try again later.";
    }

    displayError($message, "newbook.html");

} catch (Exception $e) {
    // Handle validation and other non-DB errors
    displayError($e->getMessage(), "newbook.html");
}
?>