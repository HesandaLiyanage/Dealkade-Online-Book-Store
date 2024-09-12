<?php
// Start session
session_start();

// Database connection settings
$servername = "localhost";

$username = "root";
$password = "";

$dbname = "Shopfusion";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}