<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$database = "portfolio";

try {
    // Enable MySQLi exceptions
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Optional: set charset
    $conn->set_charset("utf8mb4");

    // echo "Database connected successfully!";
} catch (mysqli_sql_exception $e) {
    // Catch connection errors
    die("DB Connection Failed: " . $e->getMessage());
}
?>
