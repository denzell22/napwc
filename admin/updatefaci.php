<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../user/user_home.php"); // Redirect to the user home page
    exit();
}

include '../components/config.php';

// Initialize variables with current facility information
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
    if (empty(trim($_POST["pricing"]))) {
        $pricing_err = "Please enter the pricing for the facility.";
    } else {
        $pricing = trim($_POST["pricing"]);
    }

    // Validate maxpax
    if (empty(trim($_POST["maxpax"]))) {
        $maxpax_err = "Please enter the maxpax for the facility.";
    } else {
        $maxpax = trim($_POST["maxpax"]);
    }

    // Check if there are no errors before updating the database
    if (empty($facilityName_err) && empty($pricing_err) && empty($maxpax_err)) {
        // Prepare an update statement
        $updateStmt = $conn->prepare("UPDATE facilities SET PRICING = ?, maxpax = ? WHERE facility_name = ?");

        if ($updateStmt) {
            // Bind parameters
            $updateStmt->bind_param("sss", $param_pricing, $param_maxpax, $param_facilityName);

            // Set parameters
            $param_pricing = $pricing;
            $param_maxpax = $maxpax;
            $param_facilityName = $facilityName;

            // Attempt to execute the statement
            if ($updateStmt->execute()) {
                $successMessage = "Facility updated successfully.";
            } else {
                $errorMessage = "Something went wrong. Please try again later.";
            }

            // Close statement
            $updateStmt->close();
        }
    }
}

// Fetch the existing facilities from the database
$facilities = [];
$result = $conn->query("SELECT facility_name FROM facilities");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $facilities[] = $row['facility_name'];
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
    <title>Update Facility</title>
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="updatefaci.css">
    
</head>

<body>
    <div class="container">
        <?php if (isset($successMessage)): ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <meta http-equiv="refresh" content="2;url=admin_home.php" />
        <?php else: ?>

        <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1 class="alert alert-info update-facility-title">Update Facility</h1>
            <div class="form-group">
                <label for="facilityName">Select Facility to Update</label>
                <select class="form-control" name="facilityName">
                    <?php
                    foreach ($facilities as $fac) {
                        echo "<option value=\"$fac\">$fac</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group <?php echo (!empty($pricing_err)) ? 'has-error' : ''; ?>">
                <label for="pricing">Pricing</label>
                <input type="text" class="form-control" name="pricing" value="<?php echo $pricing; ?>">
                <span class="help-block"><?php echo $pricing_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($maxpax_err)) ? 'has-error' : ''; ?>">
                <label for="maxpax">Maxpax</label>
                <input type="text" class="form-control" name="maxpax" value="<?php echo $maxpax; ?>">
                <span class="help-block"><?php echo $maxpax_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Update Facility">
            </div>
        </form>
        <?php endif; ?>
    </div>

    <a href="admin_home.php" class="button-goto-events">Go Back</a>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>