<?php
session_start();

// Check if the user is not logged in, redirect to the login page if true
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== 'admin')) {
    header("Location: ../user/user_home.php");
    exit();
  }

// Include the database connection file
include('../components/config.php');

$postedMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $eventTitle = $_POST['eventTitle'];
    $eventDescription = $_POST['eventDescription'];

    // Validate the form data (you can add more validation as needed)
    if (empty($eventTitle) || empty($eventDescription)) {
        echo "Please fill in all fields.";
    } else {
        // Process image upload
        $targetDir = "../uploads/events/";
        $targetFile = $targetDir . basename($_FILES["eventImage"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the image file is a actual image or fake image
        $check = getimagesize($_FILES["eventImage"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($targetFile)) {
            echo "Sorry, the file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["eventImage"]["size"] > 3000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["eventImage"]["tmp_name"], $targetFile)) {
                // Insert the event details into the database
                $insertQuery = "INSERT INTO events (event_name, event_description, event_image) VALUES ('$eventTitle', '$eventDescription', '$targetFile')";

                try {
                    $conn->query($insertQuery);
                    $postedMessage = "Event Successfully Added!";
                } catch (Exception $e) {
                    $postedMessage = "Error: " . $e->getMessage();
                }
            }    
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Add Event</title>
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_addevents.css">
   
</head>

<body>

        <div class="header">
            <?php
            // Include the header.php file
            include "../components/admin_header_loggedin.php";
            ?>
        </div>

    <div class="page-container">
        <div class="workspace-container">
            <div class="main-workspace">
                <h4>CREATE EVENT FORM</h4>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="eventTitle">Event Title:</label>
                        <input type="text" class="form-control" name="eventTitle" required>
                    </div>

                    <div class="form-group">
                        <label for="eventDescription">Event Description:</label>
                        <textarea class="form-control" name="eventDescription" rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="eventImage">Event Image:</label>
                        <input type="file" class="form-control-file" name="eventImage" accept="image/*" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Post Event</button>

                    <div id="postedMessage" class="text-success mt-3">
                        <?php echo $postedMessage; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>


        <div class="footer">
            <?php
            // Include the footer.php file
            include '../components/phone_footer.php';
            ?>
        </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>