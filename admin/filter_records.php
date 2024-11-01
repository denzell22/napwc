<?php
// Connect to the database
include '../components/config.php';

// Get the search value from the AJAX request
$searchValue = $_GET['search'];

// Escape special characters to prevent SQL injection
$searchValue = $conn->real_escape_string($searchValue);

// Query to filter records based on search value
$sql = "SELECT * FROM booking_records WHERE archived = 0 AND (
        NAME LIKE '%$searchValue%' OR
        ID LIKE '%$searchValue%' OR
        DATE LIKE '%$searchValue%' OR
        CONFIRMATION LIKE '%$searchValue%' OR
        SECTION LIKE '%$searchValue%')";

$result = $conn->query($sql);

// Check for errors in the query
if (!$result) {
    die('Query Error: ' . $conn->error);
}

// Display the filtered records
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['ID']}</td>
                <td>{$row['NAME']}</td>
                <td>{$row['lastname']}</td>
                <td>{$row['PHONE']}</td>
                <td>{$row['EMAIL']}</td>
                <td>" . date('m/d/Y', strtotime($row['DATE'])) . "</td>
                <td>{$row['SECTION']}</td>
                <td>&#8369;{$row['PRICE']}</td>
                <td>{$row['CONFIRMATION']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='9'>No matching records found.</td></tr>";
}

// Close the connection
$conn->close();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>