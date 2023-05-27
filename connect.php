<?php

// Define database connection details
$host = "localhost";
$user = "root";
$pass = "";
$db = "image_temp";

// Create database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>