<?php
// Detect if running on localhost
// $port = 3306; // Default MySQL port
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    // Localhost Database Credentials (XAMPP)
    $servername = "localhost";
    $username = "root";
    $password = ""; // No password for XAMPP by default
    $dbname = "gopal_ring_center"; // Your local database name
//     $port = 3306;
//     // echo"connected to local database";
} else {
//     // Database connection details
    $servername = "sql302.infinityfree.com";
    $username = "if0_38642090"; 
    $password = "mangorange296"; 
    $dbname = "if0_38642090_gopalringcenter"; // Your database name
    // echo"connect infinity database";
}
// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Uncomment below to test connection
// echo "Connected successfully";
?>
