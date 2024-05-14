<?php
require 'includes/db.php';
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
// Check if an 'id' is present in the URL query string
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the deletion query
    $sql = "DELETE FROM teams WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<p>Team successfully deleted.</p>";
    } else {
        echo "<p>Error deleting team: " . $stmt->error . "</p>";
    }
    $stmt->close();
    $conn->close();

    // Redirect back to the team list or dashboard after deletion
    header("Location: edit_team.php");
    exit;
} else {
    echo "<p>Invalid request. No team specified for deletion.</p>";
}
?>
