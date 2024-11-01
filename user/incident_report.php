<?php
    // Database connection details
    include '../components/config.php';

    // Process form data when the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $name = $conn->real_escape_string($_POST["name"]);
        $email = $conn->real_escape_string($_POST["email"]);
        $contactInfo = $conn->real_escape_string($_POST["contact_info"]);
        $incidentType = $conn->real_escape_string($_POST["incident_type"]);
        $incidentDescription = $conn->real_escape_string($_POST["incident_description"]);

        // Handle file upload
if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $targetDir = "../uploads/incident/";  // Specify the directory where you want to store uploaded files
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {

        // Include the file name in the database query
        $incidentImage = $targetFile;

        // Insert data into the database
        $sql = "INSERT INTO incident_report (name, email, contact_info, incident_type, incident_description, incident_image)
                VALUES ('$name', '$email', '$contactInfo', '$incidentType', '$incidentDescription', '$incidentImage')";
    
        if ($conn->query($sql) === TRUE) {
            echo "<h2>Incident Report Submitted Successfully</h2>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
    }

    // Close the database connection
    $conn->close();
    ?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="incident_report.css">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <title>Incident Report</title>
</head>

<body>

    <?php include '../components/user_header.php'; ?>

    <div class="container">
        <h1>Incident Report</h1>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="contact_info">Contact Info:</label>
            <input type="text" id="contact_info" name="contact_info" maxlength="11" required>

            <label for="incident_type">Incident Type:</label>
            <select id="incident_type" name="incident_type" required>
                <option value="security">Security Incident</option>
                <option value="technical">Technical Issue</option>
                <option value="safety">Safety Concern</option>
                <!-- Add more options as needed -->
            </select>

            <label for="incident_description">Incident Description:</label>
            <textarea id="incident_description" name="incident_description" rows="4" required></textarea>

            <label for="file">Select File:</label>
            <input type="file" id="file" name="file" accept=".jpg, .jpeg, .png, .gif">

            <input type="submit" value="Submit">

        </form>
    </div>

    <?php include '../components/phone_footer.php'; ?>

</body>

</html>
