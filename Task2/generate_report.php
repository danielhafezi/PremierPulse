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
    <link rel="stylesheet" href="css/custom.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #e8ee4e;
            color: #000000;
            padding: 20px;
            text-align: center;
        }

        nav {
            background-color: #310f38;
            padding: 10px;
            text-align: center;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 10px;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
        }

        main {
            flex: 1;
            padding: 20px;
        }

        .team-section {
            width: 100%;
            max-width: 800px;
            margin: 0 auto 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .team-section h2 {
            margin-bottom: 10px;
        }

        .chart-container {
            width: 100%;
            height: 400px;
            margin-top: 20px;
        }

        .comparative-section {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .comparative-section h2 {
            margin-bottom: 20px;
        }

        footer {
            background-color: #310f38;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Football Teams Detailed Report</h1>
    </header>

    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="report.php">Back to Team Performance Report</a></li>
            <li><a href="add_team.php">Add New Team</a></li>
            <li><a href="edit_team.php">Edit Existing Team</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <?php foreach ($teams as $team): ?>
            <div class="team-section">
                <h2><?php echo htmlspecialchars($team['name']); ?></h2>
                <p>Manager: <?php echo htmlspecialchars($team['manager']); ?></p>
                <div class="chart-container">
                    <canvas id="pieChart<?php echo $team['id']; ?>"></canvas>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (count($teams) > 1): ?>
            <div class="comparative-section">
                <h2>Comparative Analysis</h2>
                <div class="chart-container">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2023 Team Performance Inc.</p>
    </footer>

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
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
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
                    responsive: true,
                    maintainAspectRatio: false,
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
