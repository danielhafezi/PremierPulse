<?php
require 'includes/db.php';

// Fetch selected teams
$team_ids = $_POST['team_ids'];
$team_ids = implode(",", array_map('intval', $team_ids));

$sql = "SELECT * FROM teams WHERE id IN ($team_ids)";
$result = $conn->query($sql);

$teams = [];
while ($row = $result->fetch_assoc()) {
    $teams[] = $row;
}

echo "<h1>Football Teams Detailed Report</h1>";
echo "<table border='1'>";
echo "<tr><th>Club</th><th>City</th><th>Manager</th><th>Points</th><th>Wins</th><th>Losses</th><th>Draws</th><th>Played Games</th><th>Remaining Matches</th></tr>";
foreach ($teams as $team) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($team['name']) . "</td>";
    echo "<td>" . htmlspecialchars($team['city']) . "</td>";
    echo "<td>" . htmlspecialchars($team['manager']) . "</td>";
    echo "<td>" . $team['points'] . "</td>";
    echo "<td>" . $team['wins'] . "</td>";
    echo "<td>" . $team['losses'] . "</td>";
    echo "<td>" . $team['draws'] . "</td>";
    echo "<td>" . $team['played_games'] . "</td>";
    echo "<td>" . $team['remaining_matches'] . "</td>";
    echo "</tr>";
    echo "<tr><td colspan='9'><canvas id='pieChart" . $team['id'] . "'></canvas></td></tr>"; // Include a row for the pie chart
}
echo "</table>";
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var teams = <?php echo json_encode($teams); ?>;

teams.forEach(team => {
    var ctxPie = document.getElementById('pieChart' + team.id).getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Wins', 'Losses', 'Draws', 'Remaining Matches', 'Played Games'],
            datasets: [{
                data: [team.wins, team.losses, team.draws, team.remaining_matches, team.played_games],
                backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(153, 102, 255, 0.2)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)', 'rgba(255, 206, 86, 1)', 'rgba(54, 162, 235, 1)', 'rgba(153, 102, 255, 1)'],
                borderWidth: 1
            }]
        }
    });
});

if (teams.length > 1) {
    // Display the bar chart for multiple teams
    var ctxBar = document.getElementById('barChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: teams.map(team => team.name),
            datasets: [
                { label: 'Wins', data: teams.map(team => team.wins / team.played_games * 100), backgroundColor: 'rgba(75, 192, 192, 0.5)' },
                { label: 'Losses', data: teams.map(team => team.losses / team.played_games * 100), backgroundColor: 'rgba(255, 99, 132, 0.5)' },
                { label: 'Draws', data: teams.map(team => team.draws / team.played_games * 100), backgroundColor: 'rgba(255, 206, 86, 0.5)' },
                { label: 'Remaining Matches', data: teams.map(team => team.remaining_matches / team.played_games * 100), backgroundColor: 'rgba(54, 162, 235, 0.5)' }
            ]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    document.write('<canvas id="barChart"></canvas>'); // Writing the canvas element into the document
}
</script>
