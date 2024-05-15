<?php
require 'includes/db.php';
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $id = $_GET['id'];

 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $city = $_POST['city'];
        $manager = $_POST['manager'];
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

     
        $sql = "UPDATE teams SET name=?, city=?, manager=?, points=?, wins=?, losses=?, draws=?, played_games=?, remaining_matches=?, goals_for=?, goals_against=?, gd=?, topscorer=?, cleansheets=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiiiiiisiisii", $name, $city, $manager, $points, $wins, $losses, $draws, $played_games, $remaining_matches, $goals_for, $goals_against, $gd, $topscorer, $cleansheets, $id);

        if ($stmt->execute()) {
            echo "<p>Team updated successfully.</p>";
            header("location: edit_team.php"); 
            exit();
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }

   
    $sql = "SELECT * FROM teams WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $team = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Football Team</title>
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>
    <header>
        <h1>Edit Existing Team</h1>
    </header>
    <nav>
        <ul>
            <li><a href="report.php">Report</a></li>
            <li><a href="add_team.php">Add Team</a></li>
            <li><a href="edit_team.php">Edit Team</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <h2>Edit Team Form</h2>
        <form action="team_edit_form.php?id=<?php echo htmlspecialchars($id); ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($team['name']); ?>" required><br>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($team['city']); ?>" required><br>

            <label for="manager">Manager:</label>
            <input type="text" id="manager" name="manager" value="<?php echo htmlspecialchars($team['manager']); ?>" required><br>

            <label for="points">Points:</label>
            <input type="number" id="points" name="points" value="<?php echo $team['points']; ?>" required><br>

            <label for="wins">Wins:</label>
            <input type="number" id="wins" name="wins" value="<?php echo $team['wins']; ?>" required><br>

            <label for="losses">Losses:</label>
            <input type="number" id="losses" name="losses" value="<?php echo $team['losses']; ?>" required><br>

            <label for="draws">Draws:</label>
            <input type="number" id="draws" name="draws" value="<?php echo $team['draws']; ?>" required><br>

            <label for="played_games">Played Games:</label>
            <input type="number" id="played_games" name="played_games" value="<?php echo $team['played_games']; ?>" required><br>

            <label for="remaining_matches">Remaining Matches:</label>
            <input type="number" id="remaining_matches" name="remaining_matches" value="<?php echo $team['remaining_matches']; ?>" required><br>
            
            <label for="goals_for">Goals For:</label>
            <input type="number" id="goals_for" name="goals_for" value="<?php echo $team['goals_for']; ?>" required><br>

            <label for="goals_against">Goals Against:</label>
            <input type="number" id="goals_against" name="goals_against" value="<?php echo $team['goals_against']; ?>" required><br>

            <label for="gd">Goal Difference (GD):</label>
            <input type="number" id="gd" name="gd" value="<?php echo $team['gd']; ?>" required><br>

            <label for="topscorer">Top Scorer:</label>
            <input type="text" id="topscorer" name="topscorer" value="<?php echo htmlspecialchars($team['topscorer']); ?>" required><br>

            <label for="cleansheets">Clean Sheets:</label>
            <input type="number" id="cleansheets" name="cleansheets" value="<?php echo $team['cleansheets']; ?>" required><br>

            <input type="submit" value="Update Team">
        </form>
    <?php
    $stmt->close();
} else {
    echo "<p>Invalid team ID.</p>";
}
$conn->close();
?>
</main>
<footer>
    Premier League Management System © 2024
</footer>
</body>
</html>
