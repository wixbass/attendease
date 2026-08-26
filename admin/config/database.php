<?php

require_once "constants.php";
// =============================
// Database Configuration
// =============================
$host = "localhost";    
$dbname = "attendease";     
$username = "root";        
$password = "";


// =============================
// PDO Database Connection
// =============================
try {
    // Create PDO instance
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    // Error handling: throw exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Default fetch mode: associative arrays
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Optional: persistent connection for performance
    // $conn->setAttribute(PDO::ATTR_PERSISTENT, true);

} catch (PDOException $e) {
    // Stop execution if connection fails
    die("Database connection failed: " . $e->getMessage());
}

