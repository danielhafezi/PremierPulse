<?php
require 'includes/db.php';

$team_ids = $_POST['team_ids'] ?? [];

if (count($team_ids) < 1) {
    echo "No teams were selected for the report. Please go back and select at least one team.";
    exit;
}

// Convert array of team IDs from the form into a format SQL can use
$team_ids = implode(",", array_map('intval', $team_ids));

$sql = "SELECT * FROM teams WHERE id IN ($team_ids)";
$result = $conn->query($sql);

$teams = [];
while ($row = $result->fetch_assoc()) {
    $teams[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Teams Report</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Football Teams Detailed Report</h1>
    <?php foreach ($teams as $team): ?>
        <div>
            <h2><?php echo htmlspecialchars($team['name']); ?></h2>
            <p>Manager: <?php echo htmlspecialchars($team['manager']); ?></p>
            <div>
                <canvas id="pieChart<?php echo $team['id']; ?>"></canvas>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (count($teams) > 1): ?>
        <h2>Comparative Analysis</h2>
        <div>
            <canvas id="barChart"></canvas>
        </div>
    <?php endif; ?>

    <script>
        teams = <?php echo json_encode($teams); ?>;

        teams.forEach(team => {
            var ctxPie = document.getElementById('pieChart' + team.id).getContext('2d');
            new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: ['Wins', 'Losses', 'Draws', 'Remaining Matches', 'Played Games'],
                    datasets: [{
                        data: [team.wins, team.losses, team.draws, team.remaining_matches, team.played_games],
                        backgroundColor: [
                            'rgba(102, 255, 102, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(153, 102, 255, 0.6)'
                        ],
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    }]
                }
            });
        });

        if (teams.length > 1) {
            var ctxBar = document.getElementById('barChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: teams.map(team => team.name),
                    datasets: [
                        {
                            label: 'Wins',
                            data: teams.map(team => team.wins),
                            backgroundColor: 'rgba(75, 192, 192, 0.8)'
                        },
                        {
                            label: 'Losses',
                            data: teams.map(team => team.losses),
                            backgroundColor: 'rgba(255, 99, 132, 0.8)'
                        },
                        {
                            label: 'Draws',
                            data: teams.map(team => team.draws),
                            backgroundColor: 'rgba(255, 206, 86, 0.8)'
                        },
                        {
                            label: 'Remaining Matches',
                            data: teams.map(team => team.remaining_matches),
                            backgroundColor: 'rgba(54, 162, 235, 0.8)'
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
