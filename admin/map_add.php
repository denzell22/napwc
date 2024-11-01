<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$category = $name = $description = $latitude = $longitude = "";
$category_err = $name_err = $image_err = $description_err = $latitude_err = $longitude_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate category
    $category = trim($_POST["category"]);
    if (empty($category)) {
        $category_err = "Please select a category.";
    }

    // Validate name
    $name = trim($_POST["name"]);
    if (empty($name)) {
        $name_err = "Please enter a name.";
    }

    // Validate description
    $description = trim($_POST["description"]);
    if (empty($description)) {
        $description_err = "Please enter a description.";
    }

    // Validate latitude
    $latitude = trim($_POST["latitude"]);
    if (empty($latitude)) {
        $latitude_err = "Please enter latitude.";
    }

    // Validate longitude
    $longitude = trim($_POST["longitude"]);
    if (empty($longitude)) {
        $longitude_err = "Please enter longitude.";
    }

    // Check if image file is selected
if (empty($_FILES["image"]["name"])) {
    $image_err = "Please select an image file.";
} else {
    // Process image upload
    $targetDir = "../map_images/"; // Adjusted target directory path
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    
    // Check file size
    if ($_FILES["image"]["size"] > 50000000000) { // Adjusted file size limit to 5 MB
        $image_err = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowedFormats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowedFormats)) {
        $image_err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $image_err = "Sorry, your file was not uploaded.";
    } else {
        // Try to upload file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // File uploaded successfully, proceed with database insertion
            $image = $targetFile; // Store the file path in the database
        } else {
            $image_err = "Sorry, there was an error uploading your file.";
        }
    }
}


    // Check input errors before inserting in database
    if (empty($category_err) && empty($name_err) && empty($description_err) && empty($image_err) && empty($latitude_err) && empty($longitude_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO map (category, name, image, description, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)";
         
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssdd", $param_category, $param_name, $param_image, $param_description, $param_latitude, $param_longitude);
            
            // Set parameters
            $param_category = $category;
            $param_name = $name;
            $param_image = $image;
            $param_description = $description;
            $param_latitude = $latitude;
            $param_longitude = $longitude;
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: map_index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="map_add.css">

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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add a new record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-control <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>">
                                <option value="waypoint">Waypoint</option>
                                <option value="entrances">Entrances</option>
                                <option value="monuments">Monuments and Building</option>
                                <option value="attractions">Attractions</option>
                                <option value="maintenance">Maintenance and Facilities</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $category_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Image Upload</label>
                            <input type="file" name="image" class="form-control-file <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $image_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                            <span class="invalid-feedback"><?php echo $description_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="text" name="latitude" class="form-control <?php echo (!empty($latitude_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $latitude; ?>">
                            <span class="invalid-feedback"><?php echo $latitude_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="text" name="longitude" class="form-control <?php echo (!empty($longitude_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $longitude; ?>">
                            <span class="invalid-feedback"><?php echo $longitude_err; ?></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <a href="map_index.php" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
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
