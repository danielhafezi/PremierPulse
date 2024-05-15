<?php
require 'includes/db.php';
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$leagueDataJson = file_get_contents('league.json');
$leagueData = json_decode($leagueDataJson, true);

usort($leagueData['fixtures'], function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

$teamGames = [];

foreach ($leagueData['fixtures'] as $fixture) {
    $homeTeam = $fixture['home_team'];
    $awayTeam = $fixture['away_team'];

    if (!isset($teamGames[$homeTeam])) $teamGames[$homeTeam] = [];
    if (!isset($teamGames[$awayTeam])) $teamGames[$awayTeam] = [];

    $teamGames[$homeTeam][] = $fixture;
    $teamGames[$awayTeam][] = $fixture;
}

$team_ids = $_POST['team_ids'] ?? [];
if (count($team_ids) < 1) {
    echo "No teams were selected for the report. Please go back and select at least one team.";
    exit;
}

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
    <style>
        .team-report .team-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .team-report .team-header {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .team-report .team-info,
        .team-report .chart-large {
            width: 48%;
        }

        .team-report .team-info {
            margin-bottom: 20px;
        }

        .team-report .chart-large {
            display: flex;
            justify-content: flex-end;
        }

        .team-report .report-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .team-report .report-table th, .team-report .report-table td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .team-report .last-games {
            width: 100%;
            font-size: 0.9em;
        }

        .team-report .last-games ul {
            list-style: none;
            padding: 0;
        }

        .team-report .last-games li {
            line-height: 1.8;
        }

        .team-report .game-result {
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

        .team-report .game-result.W {
            background: linear-gradient(to bottom right, green, darkgreen);
        }

        .team-report .game-result.L {
            background: linear-gradient(to bottom right, blue, darkblue);
        }

        .team-report .game-result.D {
            background: linear-gradient(to bottom right, gray, darkgray);
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Detailed Report</h1>
    </header>

    <nav>
        <ul>
            <li><a href="report.php">Report</a></li>
            <li><a href="add_team.php">Add Team</a></li>
            <li><a href="edit_team.php">Edit Team</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main class="team-report">
        <?php foreach ($teams as $team): ?>
            <div class="team-section">
                <div class="team-info">
                    <h2><?php echo htmlspecialchars($team['name']); ?></h2>
                    <p>Manager: <strong><?php echo htmlspecialchars($team['manager']); ?></strong></p>
                    <p>Top Scorer: <strong><?php echo htmlspecialchars($team['topscorer']); ?></strong></p>
                </div>
                <div class="chart-large">
                    <canvas id="pieChart<?php echo $team['id']; ?>"></canvas>
                </div>
                
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Points</th>
                            <th>Wins</th>
                            <th>Losses</th>
                            <th>Draws</th>
                            <th>Goals For</th>
                            <th>Goals Against</th>
                            <th>GD</th>
                            <th>Clean Sheets</th>
                            <th>Played Games</th>
                            <th>Remaining Matches</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $team['points']; ?></td>
                            <td><?php echo $team['wins']; ?></td>
                            <td><?php echo $team['losses']; ?></td>
                            <td><?php echo $team['draws']; ?></td>
                            <td><?php echo $team['goals_for']; ?></td>
                            <td><?php echo $team['goals_against']; ?></td>
                            <td><?php echo $team['gd']; ?></td>
                            <td><?php echo $team['cleansheets']; ?></td>
                            <td><?php echo $team['played_games']; ?></td>
                            <td><?php echo $team['remaining_matches']; ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="last-games">
                    <h3>Last 5 Games</h3>
                    <ul>
                        <?php
                        $games = $teamGames[$team['name']] ?? [];
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
