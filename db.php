<?php
$host = "localhost";
$user = "root";
$pass = ""; // Default XAMPP password is empty
$db   = "ecommerce_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// In db.php, session_start(); with this:
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
