<?php
$host = "localhost";
$user = "root";     
$pass = "";         
$dbname = "book_o_rama";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>