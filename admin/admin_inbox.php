<?php
session_start();

// Check if the user is not logged in, redirect to the login page if true
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../user/user_home.php"); // Redirect to the user home page
    exit();
}

// Replace these with your actual database credentials
include('../components/config.php');

// Fetch messages from the database
$sql = "SELECT * FROM messaging";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages</title>
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin_inbox.css">

</head>

<body>

    <div class="container-fluid">

        <div class="header">
            <?php
            // Include the header.php file
            include "../components/admin_header_loggedin.php";
            ?>
        </div>

        <div class="workspace-container">

            <div class="main-workspace">
                <div id="admin-messages">
                    <!-- Button for Incident Report -->
                    <button class="report-button" onclick="window.location.href = 'admin_incident.php';">Incident Reports</button>

                    <h2>Messages</h2>

                    <?php
                    if ($result && $result->num_rows > 0) {
                        echo "<table class='table'>";
                        echo "<thead class='thead-dark'>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Message</th>
                                </tr>
                            </thead>";
                        echo "<tbody>";

                        while ($row = $result->fetch_assoc()) {
                            // Display each message in a table row
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["message"] . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<p>No Messages Found.</p>";
                    }
                    ?>
                </div>
            </div>

        </div>

        <div class="footer">
            <?php
            // Include the footer.php file
            include '../components/phone_footer.php';
            ?>
        </div>

    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>