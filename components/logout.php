<?php
session_start();

// Set the user status to inactive
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
include './components/config.php';

    $sql = "UPDATE user SET status = 'Inactive' WHERE id = $user_id";
    $conn->query($sql);

    // Close the database connection
    $conn->close();
}
// Destroy the session and redirect to the login page
session_destroy();
header("Location: home.php");
exit();
?>
