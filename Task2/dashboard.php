<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="css/custom.css">
</head>
<body>
    <header>
        <h1>Welcome to the Premier League Management System</h1>
    </header>
    <nav>
        <ul>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>  
            <li><a href="add_team.php">Add Team</a></li>
            <li><a href="edit_team.php">Edit Team</a></li>
            <li><a href="report.php">Report</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <p class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</p>
        <h2>Dashboard</h2>
        <p>Select an option from the menu to get started.</p>
    </main>
    <footer>
        <p>Premier League Management System © 2024</p>
    </footer>
</body>
</html>
