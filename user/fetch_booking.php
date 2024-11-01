<?php
session_start();
include '../components/config.php';

// Check if the ID is set
if (isset($_GET['ID'])) {
    $ID = $_GET['ID'];

    // Prepare the SQL statement to fetch booking details
    $sql = "SELECT * FROM booking_records WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $ID);

    if (!$stmt->execute()) {
        die('Execute Error: ' . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h2>Booking Details</h2>";
        echo "<p><strong>Name:</strong> " . $row['NAME'] . " " . $row['lastname'] . "</p>";
        echo "<p><strong>Phone:</strong> " . $row['PHONE'] . "</p>";
        echo "<p><strong>Email:</strong> " . $row['EMAIL'] . "</p>";
        echo "<p><strong>Date:</strong> " . date('m/d/Y', strtotime($row['DATE'])) . "</p>";
        echo "<p><strong>Section:</strong> " . $row['SECTION'] . "</p>";
        echo "<p><strong>Price:</strong> &#8369;" . $row['PRICE'] . "</p>";
        echo "<p><strong>Status:</strong> " . $row['CONFIRMATION'] . "</p>";
    } else {
        echo "<p>No booking details found.</p>";
    }

    // Close the connection
    $conn->close();
} else {
    echo "<p>Invalid booking ID.</p>";
}
