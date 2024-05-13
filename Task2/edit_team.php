<?php
require 'includes/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Teams</title>
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>
<?php
// Display all teams with edit and delete options
$sql = "SELECT id, name, city, manager, points, wins, losses, draws, played_games, remaining_matches FROM teams";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Edit or Delete Teams</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<p><strong>" . htmlspecialchars($row['name']) . "</strong> - " . htmlspecialchars($row['city']) . "</p>";
        echo "<p>Manager: " . htmlspecialchars($row['manager']) . "</p>";
        echo "<a href='team_edit_form.php?id=" . $row['id'] . "'>Edit</a> | ";
        echo "<a href='delete_team.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this team?\");'>Delete</a>";
        echo "</div>";
    }
} else {
    echo "No teams found.";
}
$conn->close();
?>
</body>
</html>
