<?php
session_start();
// Check if the user is not logged in, redirect to the login page if true
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== 'admin')) {
    header("Location: ../user/user_home.php");
    exit();
  }

// Include the database connection file
include('../components/config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if an event ID is provided for archiving
    if (isset($_POST['archive_event_id'])) {
        $eventId = $_POST['archive_event_id'];

        // Update the event status to 'archived' in the database
        $archiveSql = "UPDATE events SET status = 'archived' WHERE id = $eventId";

        if (mysqli_query($conn, $archiveSql)) {
            echo "Event archived successfully!";
        } else {
            echo "Error archiving event: " . mysqli_error($conn);
        }
    }
}

// Fetch all events from the database
$result = mysqli_query($conn, "SELECT id, event_name, event_description, event_image FROM events WHERE status = 'active'");

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_archiveevents.css">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <title>Event Archive</title>
</head>

<body>
    <div class="header">
        <?php
        // Include the header.php file
        include "../components/admin_header_loggedin.php";
        ?>
    </div>

    <div class="container">
        <h2>Event Archive</h2>

        <table class="table table-bordered mx-auto"> <!-- Center the table -->
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Event Name</th>
                    <th>Event Description</th>
                    <th>Event Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['event_name'] . "</td>";
                    echo "<td>" . $row['event_description'] . "</td>";
                    echo "<td><img src='" . $row['event_image'] . "' alt='Event Image'></td>";
                    echo "<td><form method='post' action=''>";
                    echo "<input type='hidden' name='archive_event_id' value='" . $row['id'] . "'>";
                    echo "<button type='submit' class='archive-btn'>Archive</button>";
                    echo "</form></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <?php
        // Include the footer.php file
        include '../components/phone_footer.php';
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>