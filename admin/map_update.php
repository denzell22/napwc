<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$category = $name = $image = $description = $latitude = $longitude = "";
$category_err = $name_err = $image_err = $description_err = $latitude_err = $longitude_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate category
    $input_category = trim($_POST["category"]);
    if(empty($input_category)){
        $category_err = "Please select a category.";
    } else{
        $category = $input_category;
    }
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";     
    } else{
        $name = $input_name;
    }
    
    // Validate description
    $input_description = trim($_POST["description"]);
    if(empty($input_description)){
        $description_err = "Please enter a description.";     
    } else{
        $description = $input_description;
    }
    
    // Validate latitude
    $input_latitude = trim($_POST["latitude"]);
    if(empty($input_latitude)){
        $latitude_err = "Please enter latitude.";     
    } else{
        $latitude = $input_latitude;
    }
    
    // Validate longitude
    $input_longitude = trim($_POST["longitude"]);
    if(empty($input_longitude)){
        $longitude_err = "Please enter longitude.";     
    } else{
        $longitude = $input_longitude;
    }
    
    // Check if image file is selected
    if (empty($_FILES["image"]["name"])) {
        // No new image file uploaded, retain the existing image path
        $sql = "SELECT image FROM map WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            $param_id = $id;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $existing_image);
                mysqli_stmt_fetch($stmt);
                $image = $existing_image;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        // Process image upload
        $targetDir = "../map_images/"; // Adjusted target directory path
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));


        // Check file size (adjust as needed)
        if ($_FILES["image"]["size"] > 50000000) {
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
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Store $targetFile path in a variable to use in database insertion
                $image = $targetFile;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
    // Check input errors before inserting in database
    if(empty($category_err) && empty($name_err) && empty($description_err) && empty($latitude_err) && empty($longitude_err)){
        // Prepare an update statement
        $sql = "UPDATE map SET category=?, name=?, image=?, description=?, latitude=?, longitude=? WHERE id=?";

        if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ssssddi", $param_category, $param_name, $param_image, $param_description, $param_latitude, $param_longitude, $param_id);
    
    // Set parameters
            // Set parameters
            $param_category = $category;
            $param_name = $name;
            $param_image = $image;
            $param_description = $description;
            $param_latitude = $latitude;
            $param_longitude = $longitude;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: map_index.php");
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
        $sql = "SELECT * FROM map WHERE id = ?";
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
                    $category = $row["category"];
                    $name = $row["name"];
                    $image = $row["image"];
                    $description = $row["description"];
                    $latitude = $row["latitude"];
                    $longitude = $row["longitude"];
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
    <title>Update Map Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="map_update.css">
   
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
                    <h2 class="mt-5">Update Map Record</h2>
                    <p>Please edit the input values and submit to update the map record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-control">
                                <option value="waypoint" <?php if($category == "waypoint") echo "selected"; ?>>Waypoint</option>
                                <option value="entrances" <?php if($category == "entrances") echo "selected"; ?>>Entrances</option>
                                <option value="monuments" <?php if($category == "monuments") echo "selected"; ?>>Monuments and Building</option>
                                <option value="attractions" <?php if($category == "attractions") echo "selected"; ?>>Attractions</option>
                                <option value="maintenance" <?php if($category == "maintenance") echo "selected"; ?>>Maintenance and Facilities</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $category_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                            <span class="invalid-feedback"><?php echo $description_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="text" name="latitude" class="form-control <?php echo (!empty($latitude_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $latitude; ?>">
                            <span class="invalid-feedback"><?php echo $latitude_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="text" name="longitude" class="form-control <?php echo (!empty($longitude_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $longitude; ?>">
                            <span class="invalid-feedback"><?php echo $longitude_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control-file">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="map_index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
    <div class="footer">
        <?php include '../components/footer.php'; ?>
    </div>
</body>
</html>