<?php
$mysqli = new mysqli("db", "user", "userpassword", "mydatabase");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $club_name = $_POST['club_name'];
    $city = $_POST['city'];
    $manager = $_POST['manager'];
    $wins = $_POST['wins'];
    $losses = $_POST['losses'];
    $draws = $_POST['draws'];

    $query = "INSERT INTO football_teams (club_name, city, manager, wins, losses, draws) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssiii", $club_name, $city, $manager, $wins, $losses, $draws);
    $stmt->execute();
    $stmt->close();
}

// Display existing teams
$result = $mysqli->query("SELECT * FROM football_teams");
?>
<html>
<body>
<h2>Add Football Team</h2>
<form method="post">
    Club Name: <input type="text" name="club_name"><br>
    City: <input type="text" name="city"><br>
    Manager: <input type="text" name="manager"><br>
    Wins: <input type="number" name="wins"><br>
    Losses: <input type="number" name="losses"><br>
    Draws: <input type="number" name="draws"><br>
    <input type="submit" value="Submit">
</form>
<h2>Teams List</h2>
<table border="1">
    <tr>
        <th>Club Name</th><th>City</th><th>Manager</th><th>Wins</th><th>Losses</th><th>Draws</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row["club_name"]; ?></td>
        <td><?php echo $row["city"]; ?></td>
        <td><?php echo $row["manager"]; ?></td>
        <td><?php echo $row["wins"]; ?></td>
        <td><?php echo $row["losses"]; ?></td>
        <td><?php echo $row["draws"]; ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php
$mysqli->close();
?>
</body>
</html>
