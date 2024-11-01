<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$tree_name = $scientificName = $description = $habitat = $distribution = $conservationStatus = "";
$tree_name_err = $scientificName_err = $description_err = $habitat_err = $distribution_err = $conservationStatus_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate tree name
    $input_tree_name = trim($_POST["tree_name"]);
    if(empty($input_tree_name)){
        $tree_name_err = "Please enter a tree name.";
    } else{
        $tree_name = $input_tree_name;
    }
    
    // Validate scientific name
    $input_scientificName = trim($_POST["scientific_name"]);
    if(empty($input_scientificName)){
        $scientificName_err = "Please enter a scientific name.";     
    } else{
        $scientificName = $input_scientificName;
    }
    
    // Validate description
    $input_description = trim($_POST["description"]);
    if(empty($input_description)){
        $description_err = "Please enter a description.";     
    } else{
        $description = $input_description;
    }
    
    // Validate habitat
    $input_habitat = trim($_POST["habitat"]);
    if(empty($input_habitat)){
        $habitat_err = "Please enter a habitat.";     
    } else{
        $habitat = $input_habitat;
    }
    
    // Validate distribution
    $input_distribution = trim($_POST["distribution"]);
    if(empty($input_distribution)){
        $distribution_err = "Please enter distribution information.";     
    } else{
        $distribution = $input_distribution;
    }

    // Validate conservation status
    $input_conservationStatus = trim($_POST["conservation_status"]);
    if(empty($input_conservationStatus)){
        $conservationStatus_err = "Please enter conservation status.";     
    } else{
        $conservationStatus = $input_conservationStatus;    
    }
    
    // Initialize variable to store picture path
    $tree_picture = "";

    // Check if a new file is uploaded
    if (isset($_FILES["tree_picture"]) && $_FILES["tree_picture"]["error"] == 0) {
        // Process and save the new file
        $targetDir = "../tree_images/"; // Specify the directory where you want to store uploaded files
        $targetFile = $targetDir . basename($_FILES["tree_picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check file size (adjust as needed)
        if ($_FILES["tree_picture"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats (you can expand this list)
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // If everything is OK, try to upload file
            if (move_uploaded_file($_FILES["tree_picture"]["tmp_name"], $targetFile)) {
                // Store the new picture path
                $tree_picture = $targetFile;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        // No new file uploaded, retain the existing picture path
        // Retrieve the existing picture path from the database
        $sql = "SELECT tree_picture FROM trees WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            $param_id = $id;
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $existing_tree_picture);
                    if(mysqli_stmt_fetch($stmt)){
                        $tree_picture = $existing_tree_picture;
                    }
                } else {
                    // Handle the case where no matching record is found
                }
            } else {
                // Handle the case where the query execution fails
            }
        }
    }
    
    // Check input errors before inserting in database
    if(empty($tree_name_err) && empty($scientificName_err) && empty($description_err) && empty($habitat_err) && empty($distribution_err) && empty($conservationStatus_err)){
        // Prepare an update statement
        $sql = "UPDATE trees SET tree_name=?, scientific_name=?, description=?, habitat=?, distribution=?, conservation_status=?, tree_picture=? WHERE id=?";

        if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "sssssssi", $param_tree_name, $param_scientificName, $param_description, $param_habitat, $param_distribution, $param_conservationStatus, $param_tree_picture, $param_id);
    
    // Set parameters
            // Set parameters
            $param_tree_name = $tree_name;
            $param_scientificName = $scientificName;
            $param_description = $description;
            $param_habitat = $habitat;
            $param_distribution = $distribution;
            $param_conservationStatus = $conservationStatus;
            $param_tree_picture = $tree_picture; // Assuming $tree_picture contains the file name
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: treeuniverse_index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM trees WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $tree_name = $row["tree_name"];
                    $scientificName = $row["scientific_name"];
                    $description = $row["description"];
                    $habitat = $row["habitat"];
                    $distribution = $row["distribution"];
                    $conservationStatus = $row["conservation_status"];
                    $tree_picture = $row["tree_picture"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
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
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Tree Record</title>
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="treeuniverse_update.css">
   
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
                    <h2 class="mt-5">Update Tree Record</h2>
                    <p>Please edit the input values and submit to update the tree record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Tree Name</label>
                            <input type="text" name="tree_name" class="form-control <?php echo (!empty($tree_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tree_name; ?>">
                            <span class="invalid-feedback"><?php echo $tree_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Scientific Name</label>
                            <input type="text" name="scientific_name" class="form-control <?php echo (!empty($scientificName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $scientificName; ?>">
                            <span class="invalid-feedback"><?php echo $scientificName_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                            <span class="invalid-feedback"><?php echo $description_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Habitat</label>
                            <textarea name="habitat" class="form-control <?php echo (!empty($habitat_err)) ? 'is-invalid' : ''; ?>"><?php echo $habitat; ?></textarea>
                            <span class="invalid-feedback"><?php echo $habitat_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Distribution</label>
                            <input type="text" name="distribution" class="form-control <?php echo (!empty($distribution_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $distribution; ?>">
                            <span class="invalid-feedback"><?php echo $distribution_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Conservation Status</label>
                            <input type="text" name="conservation_status" class="form-control <?php echo (!empty($conservationStatus_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $conservationStatus; ?>">
                            <span class="invalid-feedback"><?php echo $conservationStatus_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Tree Picture</label>
                            <input type="file" name="tree_picture" class="form-control-file">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="treeuniverse_index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
    <div class="footer">
        <?php include '../components/phone_footer.php'; ?>
    </div>
</body>
</html>
