<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookingId']) && isset($_POST['message'])) {
    // Add logic to insert the notification into the database
    $bookingId = $_POST['bookingId'];
    $message = $_POST['message'];

    // Perform database insertion (replace this with your database logic)
    include '../components/config.php';

    $userId = getUserIdForBooking($bookingId); // You need to implement this function

    $sql = "INSERT INTO notifications (user_id, booking_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $userId, $bookingId, $message);
    $stmt->execute();
    $stmt->close();

    $conn->close();
}
