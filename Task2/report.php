<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'includes/db.php';

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Team Report</title>";
echo "<link rel='stylesheet' href='css/custom.css'>";
echo "<style>";
echo "body { text-align: center; }"; 
echo "main { display: flex; flex-direction: column; align-items: center; }"; 
echo "h2 { margin-bottom: 20px; }"; 
echo "table { width: 100%; margin: 0 auto 20px; }"; 
echo "table, th, td { border: 1px solid #ddd; border-collapse: collapse; }"; 
echo "th, td { padding: 8px; text-align: center; }"; 
echo "th.sortable { padding-bottom: 20px; }";
echo "th.sortable:hover { cursor: pointer; text-decoration: underline; }";
echo "input[type='submit'] { margin-top: 20px; }";
echo "</style>";
echo "<script>
function sortTable(table, col, reverse) {
    var tb = table.tBodies[0],
        tr = Array.prototype.slice.call(tb.rows, 0),
        i;
    reverse = -((+reverse) || -1);
    tr = tr.sort(function (a, b) {
        return reverse * (a.cells[col].textContent.trim() - b.cells[col].textContent.trim());
    });
    for(i = 0; i < tr.length; ++i) tb.appendChild(tr[i]);
}

function makeSortable(table) {
    var th = table.tHead, i;
    th && (th = th.rows[0]) && (th = th.cells);
    if (th) {
        for (i = 0; i < th.length; i++) {
            if (th[i].classList.contains('sortable')) {
                th[i].addEventListener('click', function() {
                    var isAsc = this.classList.contains('asc');
                    Array.prototype.slice.call(th, 0).forEach(function(cell) {
                        cell.classList.remove('asc');
                        cell.classList.remove('desc');
                    });
                    this.classList.toggle('asc', !isAsc);
                    this.classList.toggle('desc', isAsc);
                    sortTable(this.closest('table'), this.cellIndex, !isAsc);
                });
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    makeSortable(document.querySelector('table.report-table'));
});
</script>";
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
echo "<form action='generate_report.php' method='post'>";
echo "<table class='report-table' style='width: 100%; margin: 0 auto 20px; border-collapse: collapse;'>";
echo "<thead>";
echo "<tr>
        <th>Select</th>
        <th>Club</th>
        <th>City</th>
        <th>Manager</th>
        <th class='sortable'><span class='sort-icons'></span>Points</th>
        <th>Top Scorer</th>
        <th class='sortable'><span class='sort-icons'></span>Wins</th>
        <th class='sortable'><span class='sort-icons'></span>Losses</th>
        <th class='sortable'><span class='sort-icons'></span>Draws</th>
        <th class='sortable'><span class='sort-icons'></span>Goals For</th>
        <th class='sortable'><span class='sort-icons'></span>Goals Against</th>
        <th class='sortable'><span class='sort-icons'></span>GD</th>
        <th class='sortable'><span class='sort-icons'></span>Clean Sheets</th>
        <th>Played Games</th>
        <th>Remaining Matches</th>
      </tr>";
echo "</thead>";
echo "<tbody>";

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
        echo "<td>" . htmlspecialchars($row['topscorer']) . "</td>";
        echo "<td>" . $row['wins'] . "</td>";
        echo "<td>" . $row['losses'] . "</td>";
        echo "<td>" . $row['draws'] . "</td>";
        echo "<td>" . $row['goals_for'] . "</td>";
        echo "<td>" . $row['goals_against'] . "</td>";
        echo "<td>" . $row['gd'] . "</td>";
        echo "<td>" . $row['cleansheets'] . "</td>";
        echo "<td>" . $row['played_games'] . "</td>";
        echo "<td>" . $row['remaining_matches'] . "</td>";
        echo "</tr>";
    } 
}

echo "</tbody>";
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
