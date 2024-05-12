<?php
include '../src/db.php'; // Include your DB connection script

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture data from form
    $team_name = $conn->real_escape_string($_POST['team_name']);
    $city = $conn->real_escape_string($_POST['city']);
    $manager = $conn->real_escape_string($_POST['manager']);
    $wins = $conn->real_escape_string($_POST['wins']);
    $losses = $conn->real_escape_string($_POST['losses']);
    $draws = $conn->real_escape_string($_POST['draws']);
    $played_games = $conn->real_escape_string($_POST['played_games']);
    $remaining_games = $conn->real_escape_string($_POST['remaining_games']);

    // SQL query to insert data into the football_teams table
    $sql = "INSERT INTO football_teams (name, city, manager, wins, losses, draws, played_games, remaining_games) 
            VALUES ('$team_name', '$city', '$manager', '$wins', '$losses', '$draws', '$played_games', '$remaining_games')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "New team added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>
