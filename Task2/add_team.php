<?php
require 'includes/db.php';
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $city = trim($_POST['city']);
    $manager = trim($_POST['manager']);
    $points = $_POST['points'];
    $wins = $_POST['wins'];
    $losses = $_POST['losses'];
    $draws = $_POST['draws'];
    $played_games = $_POST['played_games'];
    $remaining_matches = $_POST['remaining_matches'];
    $goals_for = $_POST['goals_for'];
    $goals_against = $_POST['goals_against'];
    $gd = $_POST['gd'];
    $topscorer = $_POST['topscorer'];
    $cleansheets = $_POST['cleansheets'];

    $sql = "INSERT INTO teams (name, city, manager, points, wins, losses, draws, played_games, remaining_matches, goals_for, goals_against, gd, topscorer, cleansheets) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssiiiiiisiisi", $name, $city, $manager, $points, $wins, $losses, $draws, $played_games, $remaining_matches, $goals_for, $goals_against, $gd, $topscorer, $cleansheets);

        if ($stmt->execute()) {
            echo "<p>Team successfully added.</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>New Football Team</title>
        <link rel="stylesheet" href="css/custom.css">
    </head>
    <body>
        <header>
            <h1>Add New Team</h1>
        </header>
        <nav>
            <ul>
               <li><a href="report.php">Report</a></li>
               <li><a href="add_team.php" class="active">Add Team</a></li>  
               <li><a href="edit_team.php">Edit Team</a></li>
               <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <main>
            <h3>Teams Entry Form</h3>
            <form action="add_team.php" method="post">
                <label for="name">Team Name:</label>
                <input type="text" id="name" name="name" required><br>

                <label for="city">City:</label>
                <input type="text" id="city" name="city" required><br>

                <label for="manager">Manager:</label>
                <input type="text" id="manager" name="manager" required><br>

                <label for="points">Points:</label>
                <input type="number" id="points" name="points" required><br>

                <label for="wins">Wins:</label>
                <input type="number" id="wins" name="wins" required><br>

                <label for="losses">Losses:</label>
                <input type="number" id="losses" name="losses" required><br>

                <label for="draws">Draws:</label>
                <input type="number" id="draws" name="draws" required><br>

                <label for="played_games">Played Games:</label>
                <input type="number" id="played_games" name="played_games" required><br>

                <label for="remaining_matches">Remaining Games:</label>
                <input type="number" id="remaining_matches" name="remaining_matches" required><br>
                
                <label for="goals_for">Goals For:</label>
                <input type="number" id="goals_for" name="goals_for" required><br>

                <label for="goals_against">Goals Against:</label>
                <input type="number" id="goals_against" name="goals_against" required><br>

                <label for="gd">Goal Difference (GD):</label>
                <input type="number" id="gd" name="gd" required><br>

                <label for="topscorer">Top Scorer:</label>
                <input type="text" id="topscorer" name="topscorer" required><br>

                <label for="cleansheets">Clean Sheets:</label>
                <input type="number" id="cleansheets" name="cleansheets" required><br>

                <input type="submit" value="Add Football Team">
            </form>
        </main>
        <footer>
            Premier League Management System © 2024
        </footer>
    </body>
</html>
