<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "premier_league_db";

// Create a new database connection instance
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
