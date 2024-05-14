<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'includes/db.php';

// Start HTML output
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Team Report</title>";
echo "<link rel='stylesheet' href='css/custom.css'>";
echo "<style>";
echo "body { text-align: center; }"; // Ensure body content is centered
echo "main { display: flex; flex-direction: column; align-items: center; }"; // Using flexbox to center children of <main>
echo "h2 { margin-bottom: 20px; }"; // Adding more space below the header
echo "table { width: 90%; max-width: 800px; margin: 0 auto 20px; }"; // Limit table width and center it
echo "table, th, td { border: 1px solid #ddd; border-collapse: collapse; }"; // Stylish table borders
echo "th, td { padding: 8px; text-align: center; }"; // Central alignment and padding in table cells
echo "input[type='submit'] { margin-top: 20px; }"; // Space above the submit button
echo "</style>";
echo "</head>";
echo "<body>";

// Header
echo "<header><h1>Team Performance Report</h1></header>";

// Navigation
echo "<nav><ul>
      <li><a href='report.php' class='active'>Report</a></li>  
      <li><a href='add_team.php'>Add Team</a></li>
      <li><a href='edit_team.php'>Edit Team</a></li>
      <li><a href='logout.php'>Logout</a></li>
      </ul></nav>";

// Main content
echo "<main>";
echo "<h2>Premier League Table</h2>"; // Title with margin bottom for space
echo "<form action='generate_report.php' method='post'>";
echo "<table class='report-table' style='margin: 0 auto; width: 90%; max-width: 800px; border-collapse: collapse;'>";
echo "<tr>
        <th>Select</th>
        <th>Club</th>
        <th>City</th>
        <th>Manager</th>
        <th>Points</th>
        <th>Wins</th>
        <th>Losses</th>
        <th>Draws</th>
        <th>Played Games</th>
        <th>Remaining Matches</th>
      </tr>";

$sql = "SELECT * FROM teams ORDER BY points DESC";
$result = $conn->query($sql);
if (!$result) {
    echo "Error accessing database: " . $conn->error;
} else {
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
}

echo "</table>";
echo "<input type='submit' value='Generate Report'>";
echo "</form>";
echo "</main>";

$conn->close();

// Footer
echo "<footer><p>Premier League Management System © 2024</p></footer>";

echo "</body>";
echo "</html>";
?>
