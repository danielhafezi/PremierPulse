<?php
// Database credentials
$host = 'db'; // Use the Docker service name
$dbname = 'csym019db';
$username = 'student';
$password = 'csym019';

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// The connection will now be used by other scripts that include this file
