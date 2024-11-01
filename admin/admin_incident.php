<?php

session_start();

// Check if the user is not logged in, redirect to the login page if true
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== 'admin')) {
    header("Location: ../user/user_home.php");
    exit();
  }

// Fetch incident reports from the database
include '../components/config.php';

$sql = "SELECT id, name, email, contact_info, incident_type, incident_description, timestamp FROM incident_report";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports</title>
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin_incident.css">

</head>

<body>
    <header>
        <?php
        // Include admin header if needed
        include '../components/admin_header_loggedin.php';
        ?>
    </header>

    <h1 class="mt-3">INCIDENT REPORTS</h1>

    <div class="container mt-4">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact Info</th>
                        <th>Incident Type</th>
                        <th>Incident Description</th>
                        <th>Timestamp</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['contact_info']}</td>
                                    <td>{$row['incident_type']}</td>
                                    <td>{$row['incident_description']}</td>
                                    <td>{$row['timestamp']}</td>
                                    <td><button class='view-btn' onclick='viewIncident({$row['id']})'>View</button></td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No incident reports found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <?php
        // Include the footer.php file
        include '../components/phone_footer.php';
        ?>
    </div>
    
    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function viewIncident(incidentId) {
            window.location.href = 'view_description.php?id=' + incidentId;
        }

        function goBack() {
            window.location.href = '../admin/admin_inbox.php';
        }
    </script>
</body>

</html>