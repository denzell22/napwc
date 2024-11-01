<?php
session_start();
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM trees WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $treeName = $row["tree_name"];
                $scientificName = $row["scientific_name"];
                $description = $row["description"];
                $habitat = $row["habitat"];
                $distribution = $row["distribution"];
                $conservationStatus = $row["conservation_status"];
                $treePicture = $row["tree_picture"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: treeuniverse_error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: treeuniverse_error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Tree Record</title>
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
       
        .custom-bg {
            font-family: Arial, sans-serif;
            background-color: #4E7130; /* Maintain the existing background color */
            margin: 0;
            padding: 0;
        }
        .wrapper {
            width: 800px;
            margin: 50px auto; /* Center the container */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #C9B590; /* Set a white background */
        }
        .thumbnail {
            width: 400px;
            height: 400px;
            margin: 0 auto 20px; /* Center the thumbnail and add bottom margin */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; /* Hide overflowing image */
            border-radius: 8px; /* Add border-radius for the thumbnail */
        }
        .thumbnail img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            font-size: 25px; /* Increase label font size */
            text-decoration: underline; /* Add underline to labels */
        }
        .btn-primary {
            background-color: #4E7130;
            border-color: #4E7130;
        }
        .btn-primary:hover {
            background-color: #405925;
            border-color: #405925;
        }
    </style>
</head>
<body class="custom-bg">
<div class="header">
        <?php
        // Include the header.php file
        include '../components/admin_header_loggedin.php';
        ?>
    </div>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3"></h1>
                    <div class="thumbnail">
                        <img src="<?php echo $treePicture; ?>" alt="Tree Picture">
                    </div>
                    <div class="form-group">
                        <label>Tree Name:</label>
                        <p><b><?php echo $treeName; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Scientific Name:</label>
                        <p><b><?php echo $scientificName; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <p><b><?php echo $description; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Habitat:</label>
                        <p><b><?php echo $habitat; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Distribution:</label>
                        <p><b><?php echo $distribution; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Conservation Status:</label>
                        <p><b><?php echo $conservationStatus; ?></b></p>
                    </div>
                    <p><a href="treeuniverse_index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
    <div class="footer">
        <?php include '../components/phone_footer.php'; ?>
    </div>
</body>
</html>
