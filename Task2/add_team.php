<?php
require 'includes/db.php';

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

    $sql = "INSERT INTO teams (name, city, manager, points, wins, losses, draws, played_games, remaining_matches) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssiissss", $name, $city, $manager, $points, $wins, $losses, $draws, $played_games, $remaining_matches);

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
               <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="report.php">Premier League Report</a></li>
                <li><a href="edit_team.php">Edit Existing Team</a></li>
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

    <input type="submit" value="Add Football Team">
</form>

        </main>
        <footer>
            &copy; CSYM019 2024
        </footer>
    </body>
</html>
