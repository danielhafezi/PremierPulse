<?php
require 'includes/db.php';

echo "<h2>Edit Team Information</h2>";

$sql = "SELECT id, name, city, manager, points, wins, losses, draws, played_games, remaining_matches FROM teams";
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
        echo "<p>Manager: " . htmlspecialchars($row['manager']) . "</p>";
        // Add more details as needed...
        echo "<a href='edit_team.php?id=" . $row['id'] . "'>Edit</a>";
        echo "</div>";
    }
} else {
    echo "Error fetching teams: " . $conn->error;
}
?>
