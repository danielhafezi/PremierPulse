<?php
require 'includes/db.php';

// Start HTML output
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Team Report</title>";
echo "<link rel='stylesheet' href='css/custom.css'>";  // Link to your CSS file
echo "</head>";
echo "<body>";

// Fetch all teams ordered by points
$sql = "SELECT * FROM teams ORDER BY points DESC";
$result = $conn->query($sql);

echo "<form action='generate_report.php' method='post'>";
echo "<table border='1'>";
echo "<tr><th>Select</th><th>Club</th><th>City</th><th>Manager</th><th>Points</th><th>Wins</th><th>Losses</th><th>Draws</th><th>Played Games</th><th>Remaining Matches</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td><input type='checkbox' name='team_ids[]' value='" . $row['id'] . "'></td>";
    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['city']) . "</td>";
    echo "<td>" . htmlspecialchars($row['manager']) . "</td>";
    echo "<td>" . $row['points'] . "</td>";
    echo "<td>" . $row['wins'] . "</td>";
    echo "<td>" . $row['losses'] . "</td>";
    echo "<td>" . $row['draws'] . "</td>";
    echo "<td>" . $row['played_games'] . "</td>";
    echo "<td>" . $row['remaining_matches'] . "</td>";
    echo "</tr>";
}

echo "</table>";
echo "<input type='submit' value='Generate Report'>";
echo "</form>";
$conn->close();

// End HTML output
echo "</body>";
echo "</html>";
?>
