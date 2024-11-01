<?php
session_start();
$date = $_GET['date'];
$facilityName = $_GET['facility_name'];
$facilityPrice = isset($_GET['facility_price']) ? $_GET['facility_price'] : 0; // Default to 0 if not set

if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or any other action you prefer
    header("Location: user_home.php");
    exit(); // Ensure that the script stops executing after the redirect
}

// Fetch user information from the database
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    include '../components/config.php';
    
    // Prepare and execute a query to get user information
    $stmt = $conn->prepare("SELECT first_name, last_name, contact, email FROM user WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    
    // Bind the results
    $stmt->bind_result($first_name, $last_name, $contact, $email);
    
    // Fetch the result
    $stmt->fetch(); 
    
    // Close the statement
    $stmt->close();
    
    // Close the database connection
    $conn->close();
}

// Fetch the facility information from the database based on the provided facility name
include '../components/config.php';
$facilityInfo = $conn->query("SELECT PRICING, maxpax FROM facilities WHERE facility_name = '$facilityName'");
if ($facilityInfo) {
    $facilityData = $facilityInfo->fetch_assoc();
    $price = $facilityData['PRICING'];
    $maxpax = $facilityData['maxpax'];
} else {
    // Handle the case when facility information is not found
    $price = 0; // Default price
    $maxpax = "No max pax found";
}


if (isset($_POST['submit'])) {
    $name = isset($_POST['FIRSTNAME']) ? $_POST['FIRSTNAME'] : '';
    $lastname = isset($_POST['LASTNAME']) ? $_POST['LASTNAME'] : '';
    $phone = isset($_POST['PHONE']) ? $_POST['PHONE'] : '';
    $formEmail = isset($_POST['EMAIL']) ? $_POST['EMAIL'] : '';
    $time_of_leave = '17:00';
    $arrival_time = '08:00';
    $section = isset($_POST['SECTION']) ? $_POST['SECTION'] : '';
    $confirmation = isset($_POST['CONFIRMATION']) ? $_POST['CONFIRMATION'] : 'PENDING';

    // Fetch the price of the selected facility from the database
    include '../components/config.php';
    $stmt = $conn->prepare("SELECT PRICING, maxpax FROM facilities WHERE facility_name = ?");
    $stmt->bind_param('s', $section);
    $stmt->execute();
    $stmt->bind_result($price, $maxpax); // Bind both price and maxpax
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    // Ensure the price is fetched properly
    if ($price !== null) {
        // Assign the fetched price to the hidden input field in the form
        echo '<input type="hidden" name="PRICE" value="' . $price . '">';
    } else {
        // If price is not found, handle accordingly (e.g., display an error message)
        echo '<p>Error: Price not found for selected facility</p>';
    }
    // Format the time for display in the admin panel
    $formatted_arrival_time = $arrival_time;

    include '../components/config.php';

    $sql = "INSERT INTO booking_records 
            (NAME, lastname, PHONE, EMAIL, DATE, TIME_OF_LEAVE, ARRIVAL_TIME, SECTION, CONFIRMATION, PRICE)  
            VALUES ('$name','$lastname', '$phone', '$formEmail', '$date', '$time_of_leave', '$formatted_arrival_time', '$section', '$confirmation', '$price')";

    if ($conn->query($sql)) {
        $message = "<div class='alert alert-success'>Booking Successful</div>";
    } else {
        $message = "<div class='alert alert-danger'>Booking was not Successful</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Booking System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="book_process.css">
   
</head>

<body>

    <!--navbar-->
    <div class="header">
        <?php include '../components/user_header2.php';?>
    </div>

    <div class="container">
        <?php echo isset($message) ? $message : ''; ?>

        <h1 class="alert alert-danger booking-title">Book for Date: <?php echo date('m/d/Y', strtotime($date)); ?></h1>

        <div class="booking-modal">
            <form action="" method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="FIRSTNAME">First Name</label>
                    <input type="text" class="form-control" name="FIRSTNAME" value="<?php echo isset($first_name) ? $first_name : ''; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="LASTNAME">Last Name</label>
                    <input type="text" class="form-control" name="LASTNAME" value="<?php echo isset($last_name) ? $last_name : ''; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="EMAIL">Email</label>
                    <input type="email" class="form-control" name="EMAIL" value="<?php echo isset($email) ? $email : ''; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="PHONE">Phone</label>
                    <input type="text" class="form-control" name="PHONE" pattern="[0-9]{11}" title="Please enter a valid 11-digit contact number" maxlength="11" value="<?php echo isset($contact) ? $contact : ''; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="SECTION">Selected Facility</label>
                    <input type="text" class="form-control" name="SECTION" value="<?php echo $facilityName; ?>" readonly>
                </div>

                <button type="submit" name="submit" class="btn btn-primary">Submit</button>

                <div class="note">
                    <p style="color: red;">Note:</p>
                    <p>Please make sure to review and confirm your booking information at your Appointments page.</p>
                    <p>There are limits on the number of persons allowed in certain facilities.</p>
                    <?php
                    if (isset($maxpax)) {
                        echo "Maximum Capacity: $maxpax<br>";
                    }
                    else{echo "No max pax foound";}
                    if (isset($facilityPrice)) {
                        echo "Facility Price: $facilityPrice<br>";
                    }
                    ?>
                </div>

            </form>
        </div>
    </div>

    <!--Booking Success Modal-->
    <div id="bookingSuccessModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="redirectToBookingHome()">&times;</button>
                    <h4 class="modal-title">Booking Successful</h4>
                </div>
                <div class="modal-body">
                    <p>Your booking has been successfully submitted.</p></br>
                    <p>Please make sure to review and confirm your booking information at your Appointments page.</p>
                    <a href="myappointments.php">Click here to review your booking details.</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script>
        // Show booking success modal if message contains "Booking Successful"
        $(document).ready(function () {
            <?php if (isset($message) && strpos($message, 'Booking Successful') !== false): ?>
                $('#bookingSuccessModal').modal('show');
            <?php endif; ?>
        });

        function redirectToBookingHome() {
            window.location.href = 'booking_home.php';
        };
    </script>

</body>

</html>