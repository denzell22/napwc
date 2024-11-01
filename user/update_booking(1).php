<?php
include '../components/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingID = $_POST['ID'];
    $status = $_POST['status'];

    // Update the status in the database based on the action
    $updateSql = "UPDATE booking_records SET CONFIRMATION = ? WHERE ID = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param('si', $status, $bookingID);

    if ($updateStmt->execute()) {
        echo "Booking status updated successfully.";
    } else {
        echo "Error updating booking status: " . $conn->error;
    }

    $updateStmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>