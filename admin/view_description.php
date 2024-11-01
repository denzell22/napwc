<?php

// Check if the incident ID is provided
if (!isset($_GET['id'])) {
    echo "Incident ID not provided.";
    exit;
}

$incidentId = $_GET['id'];

// Fetch the incident details from the database based on the provided ID
include ('../components/config.php');

$sql = "SELECT incident_description, name, email, contact_info, incident_type, timestamp, incident_image FROM incident_report WHERE id = $incidentId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $name = $row['name'];
    $email = $row['email'];
    $contactInfo = $row['contact_info'];
    $incidentType = $row['incident_type'];
    $incidentDescription = $row['incident_description'];
    $timestamp = $row['timestamp'];
    $image = $row['incident_image'];
    // Display the incident details
    echo "<html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Incident Details</title>

        <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #4C6444;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #3498db;
        }

        .incident-box {
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
            width: 60%;
            max-width: 600px;
            overflow-wrap: break-word;
        }

        p {
            margin: 10px 0;
        }

        img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
        }

        .back-button {
            margin-top: 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #d90429;
        }
        </style>
        </head>
            
        <body>
                <h1>Incident Details</h1>
                <div class='incident-box'>
                    <p><strong>Name:</strong> $name</p>
                    <p><strong>Email:</strong> $email</p>
                    <p><strong>Contact Info:</strong> $contactInfo</p>
                    <p><strong>Incident Type:</strong> $incidentType</p>
                    <hr>
                    <p><strong>Incident Description:</strong></p>
                    <p>$incidentDescription</p>
                    <hr>
                    <p><strong>Timestamp:</strong> $timestamp</p>
                    <strong>Image:</strong> <img src='$image' alt='Incident Image' style='max-width:100%;'>
                </div>
                <button class='back-button' onclick='goBack()'>Back</button>
                <script>
                    function goBack() {
                        window.location.href = '../admin/admin_incident.php';
                    }
                </script>
        </body>
        </html>";
} else {
    echo "Incident not found.";
}

// Close the database connection
$conn->close();
?>