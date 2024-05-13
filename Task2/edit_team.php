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
    <header>
        <h1>Edit Team Information</h1>
    </header>

    <nav>
        <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="add_team.php">Add New Team</a></li>
        <li><a href="report.php">Report</a></li>
        <li><a href="logout.php">Logout</a></li>

        </ul>
    </nav>

    <main>
        <?php
        $sql = "SELECT id, name, city, manager, points, wins, losses, draws, played_games, remaining_matches FROM teams";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Edit or Delete Teams</h2>";
            echo "<table>";
            echo "<tr><th>Name</th><th>City</th><th>Manager</th><th>Options</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['city']) . "</td>";
                echo "<td>" . htmlspecialchars($row['manager']) . "</td>";
                echo "<td><a href='team_edit_form.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_team.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this team?\");'>Delete</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No teams found.";
        }
        ?>
    </main>

    <footer>
        <p>Premier League Management System © 2024</p>
    </footer>

    <?php $conn->close(); ?>
</body>
</html>
