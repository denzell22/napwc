<?php
// Include the database configuration
include '../components/config.php';

// Get the sorting order from the AJAX request
$sortOrder = $_GET['sortOrder'];

// Query to retrieve booking records sorted by date
$sql = "SELECT * FROM booking_records WHERE archived = 0 ORDER BY DATE $sortOrder";

$result = $conn->query($sql);

// Check for errors in the query
if (!$result) {
    die('Query Error: ' . $conn->error);
}

// Display the sorted records
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

// Close the database connection
$conn->close();
?>