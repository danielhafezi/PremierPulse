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
        <p>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</p>
    </header>
    <nav>
        <ul>
            <li><a href="add_team.php">Add New Team</a></li>
            <li><a href="edit_team.php">Edit Existing Team</a></li>
            <li><a href="report.php">Report</a></li>
            <li><a href="logout.php">Logout</a></li> <!-- Logout button moved here -->
        </ul>
    </nav>
    <main>
        <h2>Dashboard</h2>
        <p>Select an option from the menu to get started.</p>
    </main>
    <footer>
        <p>Premier League Management System © 2024</p>
    </footer>
</body>
</html>
