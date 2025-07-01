<?php
// Database connection settings
$host = 'localhost'; // Database host
$dbname = 'aura_co_db'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Create PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode to exception for better error handling
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, display error message
    die('Database Connection Failed: ' . $e->getMessage());
}

?>
