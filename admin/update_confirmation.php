<?php
if (isset($_GET['id'])) {
    $recordId = $_GET['id'];

    include '../components/config.php';

    // Query to update the confirmation status
    $updateSql = "UPDATE booking_records SET CONFIRMATION = 'CONFIRMED' WHERE ID = $recordId";
    $updateResult = $conn->query($updateSql);

    // Check for errors in the query
    if (!$updateResult) {
        $response = array('status' => 'error', 'message' => 'Update failed: ' . $conn->error);
    } else {
        $response = array('status' => 'success', 'message' => 'Confirmation status updated successfully.');
    }

    // Close the connection
    $conn->close();

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
