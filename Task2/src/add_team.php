<?php
// Database connection details (replace with your actual credentials)
$servername = "mysql"; 
$username = "student";
$password = "csym019";
$dbname = "Internet_programming"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Process form data when it's submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $team_name = $_POST["team_name"];
  $city = $_POST["city"];
  $manager = $_POST["manager"];
  $played = $_POST["played"];
  $wins = $_POST["wins"];
  $losses = $_POST["losses"];
  $draws = $_POST["draws"];
  $points = $_POST["points"]; 

  // SQL query to insert data
  $sql = "INSERT INTO teams (team_name, city, manager, played, wins, losses, draws, points)
  VALUES ('$team_name', '$city', '$manager', '$played', '$wins', '$losses', '$draws', '$points')";

  if ($conn->query($sql) === TRUE) {
    echo "New team added successfully!";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

$conn->close();
?>
