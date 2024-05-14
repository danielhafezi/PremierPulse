<?php
require 'includes/db.php';

// Load the JSON file
$leagueDataJson = file_get_contents('league.json');
$leagueData = json_decode($leagueDataJson, true);

// Extract and sort fixtures by date
usort($leagueData['fixtures'], function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

// Group fixtures by team
$teamGames = [];
foreach ($leagueData['fixtures'] as $fixture) {
    $teamGames[$fixture['home_team']][] = $fixture;
    $teamGames[$fixture['away_team']][] = $fixture;
}

// Get selected team IDs from POST request
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
        
        .last-games {
            margin-top: 15px;
            font-size: 0.9em;
        }

        .game-result {
            display: inline-block;
            width: 20px;
            height: 20px;
            line-height: 20px;
            text-align: center;
            margin-right: 4px;
            border-radius: 4px;
            font-weight: bold;
            color: white;
        }

        .game-result.W {
            background: linear-gradient(to bottom right, green, darkgreen);
        }

        .game-result.L {
            background: linear-gradient(to bottom right, blue, darkblue);
        }

        .game-result.D {
            background: linear-gradient(to bottom right, gray, darkgray);
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
            <li><a href="add_team.php">Add Team</a></li>
            <li><a href="edit_team.php">Edit Team</a></li>
            <li><a href="report.php">Report</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <?php foreach ($teams as $team): ?>
            <div class="team-section">
                <h2><?php echo htmlspecialchars($team['name']); ?></h2>
                <p>Manager: <?php echo htmlspecialchars($team['manager']); ?></p>
                
                <!-- Last 5 games section -->
                <div class="last-games">
                    <h3>Last 5 Games</h3>
                    <ul>
                        <?php
                        $games = array_merge(
                            $teamGames[$team['name']] ?? [],
                            $teamGames[$team['city']] ?? []
                        );
                        $last5Games = array_slice($games, 0, 5);
                        foreach ($last5Games as $game) {
                            $isHome = $game['home_team'] == $team['name'];
                            $opponent = $isHome ? $game['away_team'] : $game['home_team'];
                            $score = $isHome ? "{$game['home_score']} - {$game['away_score']}" : "{$game['away_score']} - {$game['home_score']}";
                            $result = $isHome ? ($game['home_score'] > $game['away_score'] ? 'W' : ($game['home_score'] < $game['away_score'] ? 'L' : 'D')) : ($game['away_score'] > $game['home_score'] ? 'W' : ($game['away_score'] < $game['home_score'] ? 'L' : 'D'));
                            echo "<li><span class='game-result $result'>$result</span>{$game['date']} vs {$opponent}: {$score}</li>";
                        }
                        ?>
                    </ul>
                </div>
                
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
        <p>Premier League Management System © 2024</p>
    </footer>

    <script>
        const teams = <?php echo json_encode($teams); ?>;
        teams.forEach(team => {
            const ctxPie = document.getElementById('pieChart' + team.id).getContext('2d');
            new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: ['Wins', 'Losses', 'Draws', 'Remaining Matches'],
                    datasets: [{
                        data: [team.wins, team.losses, team.draws, team.remaining_matches],
                        backgroundColor: [
                            'rgba(55, 126, 34)',
                            'rgba(0, 0, 245)',
                            'rgba(128, 128, 128)',
                            'rgba(144, 148, 48)'
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
            const ctxBar = document.getElementById('barChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: teams.map(team => team.name),
                    datasets: [
                        {
                            label: 'Wins',
                            data: teams.map(team => team.wins),
                            backgroundColor: 'rgba(55, 126, 34)'
                        },
                        {
                            label: 'Losses',
                            data: teams.map(team => team.losses),
                            backgroundColor: 'rgba(0, 0, 245)'
                        },
                        {
                            label: 'Draws',
                            data: teams.map(team => team.draws),
                            backgroundColor: 'rgba(128, 128, 128)'
                        },
                        {
                            label: 'Remaining Matches',
                            data: teams.map(team => team.remaining_matches),
                            backgroundColor: 'rgba(144, 148, 48)'
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
