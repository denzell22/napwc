<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../user/user_home.php"); // Redirect to the user home page
    exit();
}

include '../components/config.php';

// Define variables and initialize with empty values
$facilityName = $pricing = $maxpax = '';
$facilityName_err = $pricing_err = $maxpax_err = '';

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate facility name
    if (empty(trim($_POST["facilityName"]))) {
        $facilityName_err = "Please enter a facility name.";
    } else {
        $facilityName = trim($_POST["facilityName"]);
    }

    // Validate pricing
    if (empty(trim($_POST["PRICING"]))) {
        $pricing_err = "Please enter the pricing for the facility.";
    } elseif (intval($_POST["PRICING"]) < 0) {
        $pricing_err = "Price cannot be negative.";
    } else {
        $pricing = trim($_POST["PRICING"]);
    }

    // Validate maxpax
    if (empty(trim($_POST["maxpax"]))) {
        $maxpax_err = "Please enter the maximum capacity for the facility.";
    } else {
        $maxpax = trim($_POST["maxpax"]);
    }

    // Check if there are no errors before inserting into the database
    if (empty($facilityName_err) && empty($pricing_err) && empty($maxpax_err)) {
        // Prepare an insert statement
        $insertStmt = $conn->prepare("INSERT INTO facilities (facility_name, PRICING, maxpax) VALUES (?, ?, ?)");

        if ($insertStmt) {
            // Bind parameters
            $insertStmt->bind_param("sss", $param_facilityName, $param_pricing, $param_maxpax);

            // Set parameters
            $param_facilityName = $facilityName;
            $param_pricing = $pricing;
            $param_maxpax = $maxpax;

            // Attempt to execute the statement
            if ($insertStmt->execute()) {
                $facilityAdded = true; // Facility added successfully
                // Clear input fields
                $facilityName = $pricing = $maxpax = '';
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $insertStmt->close();
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Facility</title>
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="addfaci.css">
   
</head>

<body>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1 class="alert alert-info add-facility-title">Add Facility</h1>
            <div class="form-group <?php echo (!empty($facilityName_err)) ? 'has-error' : ''; ?>">
                <label for="facilityName">Facility Name</label>
                <input type="text" class="form-control" name="facilityName" value="<?php echo $facilityName; ?>">
                <span class="help-block"><?php echo $facilityName_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($pricing_err)) ? 'has-error' : ''; ?>">
                <label for="PRICING">Pricing</label>
                <input type="number" class="form-control" name="PRICING" value="<?php echo $pricing; ?>">
                <span class="help-block"><?php echo $pricing_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($maxpax_err)) ? 'has-error' : ''; ?>">
                <label for="maxpax">Maximum Capacity</label>
                <input type="text" class="form-control" name="maxpax" value="<?php echo $maxpax; ?>">
                <span class="help-block"><?php echo $maxpax_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Add Facility">
            </div>
        </form>

        <!-- Success Message -->
        <?php if (isset($facilityAdded) && $facilityAdded): ?>
            <div class="alert alert-success">Facility added successfully.</div>
            <meta http-equiv="refresh" content="3">
        <?php endif; ?>
    </div>

    <a href="../admin/admin_home.php" class="button-goto-events">Go Back</a>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            // Show success message and refresh after 3 seconds
            $('.alert-success').fadeIn('slow').delay(3000).fadeOut('slow');
        });
    </script>
</body>

</html>