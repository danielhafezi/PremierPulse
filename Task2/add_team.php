<?php
// Include db and header
require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $city = trim($_POST['city']);
    // Additional fields as necessary

    // Prepare an INSERT statement
    $sql = "INSERT INTO teams (name, city) VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $name, $city);

        if ($stmt->execute()) {
            echo "<p>Team successfully added.</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>New Football Team</title>
        <link rel="stylesheet" href="css/styles.css"> <!-- Ensure the path to your CSS is correct -->
    </head>
    <body>
        <header>
            <h3>CSYM019 - Premier League Results</h3>
        </header>
        <nav>
            <ul>
                <li><a href="report.php">Premier League Report</a></li>
                <li><a href="add_team.php">Add New Football Team</a></li>
            </ul>
        </nav>
        <main>
            <h3>Sample Football Teams Entry Form</h3>
            <form action="add_team.php" method="post">
                <label for="name">Team Name:</label>
                <input type="text" id="name" name="name" required><br>

                <label for="city">City:</label>
                <input type="text" id="city" name="city" required><br>

                <!-- Additional fields -->
                
                <input type="submit" value="Add Football Team">
            </form>
        </main>
        <footer>
            &copy; CSYM019 2024
        </footer>
    </body>
</html>
