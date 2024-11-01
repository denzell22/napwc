<?php
session_start();

// Initialize a variable to track submission status
$submissionStatus = '';

include '../components/config.php';


// Function to sanitize input data
function sanitizeInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitizeInput($_POST["name"]);
    $email = sanitizeInput($_POST["email"]);
    $message = sanitizeInput($_POST["message"]);

    // Insert data into the database
    $sql = "INSERT INTO messaging (name, email, message) VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        $submissionStatus = 'success';
        $_SESSION['submission_success'] = true;
    } else {
        $submissionStatus = 'error';
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$showSubmissionSuccess = isset($_SESSION['submission_success']) && $_SESSION['submission_success'];
if ($showSubmissionSuccess) {
    // Unset the session variable
    unset($_SESSION['submission_success']);
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="messaging_loggedout.css">
   
</head>

<body>
    <div class="header">
         <?php
         // Include the header.php file
         include '../components/user_header.php';
         ?>
    </div>

    <div class="container-fluid content-container">
    <div class="contact-form">
        <h4>Contact Us</h4>
        <?php if ($showSubmissionSuccess): ?>
            <div class="alert alert-success" role="alert">
                Message sent successfully!
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="message">Your Message:</label>
                <textarea id="message" name="message" rows="6" class="form-control" required></textarea>
            </div>
            <div class="form-group text-center"> <!-- Added text-center class here -->
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                <br>
                <button class="btn btn-primary" onclick="window.location.href = 'incident_report.php';">
                    <div class="btn-icon">
                        <span>Was there an Incident? Report it Now</span>
                        <i class="fa fa-warning"></i>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>

    <div class="footer">
            <?php
            // Include the footer.php file
            include '../components/phone_footer.php';
            ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>