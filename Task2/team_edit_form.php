<?php
require 'includes/db.php';

// Check if an ID was passed and it's a valid number
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $id = $_GET['id'];

    // Handle the form submission
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

        // Update database
        $sql = "UPDATE teams SET name=?, city=?, manager=?, points=?, wins=?, losses=?, draws=?, played_games=?, remaining_matches=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiissssi", $name, $city, $manager, $points, $wins, $losses, $draws, $played_games, $remaining_matches, $id);

        if ($stmt->execute()) {
            echo "<p>Team updated successfully.</p>";
            header("location: edit_team.php"); // Redirect to the list page
            exit();
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }

    // Fetch current data of the team
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="report.php">Report</a></li>
            <li><a href="add_team.php">Add New Football Team</a></li>
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

            <input type="submit" value="Update Team">
        </form>
    <?php
    $stmt->close();
} else {
    echo "<p>Invalid team ID.</p>";
}
$conn->close();
?>
