<?php
if (isset($_GET['id'])) {
    $recordId = $_GET['id'];

    // Connect to the database
    include '../components/config.php';

    // Query to archive the record
    $archiveSql = "UPDATE booking_records SET archived = 0 WHERE ID = $recordId";
    $archiveResult = $conn->query($archiveSql);

    // Check for errors in the query
    if (!$archiveResult) {
        die('Archive Error: ' . $conn->error);
    }

    // Close the connection
    $conn->close();
}
?>