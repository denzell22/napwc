<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$tree_name = $scientific_name = $description = $habitat = $distribution = $conservation_status = "";
$tree_name_err = $scientific_name_err = $description_err = $habitat_err = $distribution_err = $conservation_status_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate tree name
    $tree_name = trim($_POST["tree_name"]);
    if (empty($tree_name)) {
        $tree_name_err = "Please enter a tree name.";
    }

    // Validate scientific name
    $scientific_name = trim($_POST["scientific_name"]);
    if (empty($scientific_name)) {
        $scientific_name_err = "Please enter a scientific name.";
    }

    // Validate description
    $description = trim($_POST["description"]);
    if (empty($description)) {
        $description_err = "Please enter a description.";
    }

    // Validate habitat
    $habitat = trim($_POST["habitat"]);
    if (empty($habitat)) {
        $habitat_err = "Please enter a habitat.";
    }

    // Validate distribution
    $distribution = trim($_POST["distribution"]);
    if (empty($distribution)) {
        $distribution_err = "Please enter a distribution.";
    }

    // Validate conservation status
    $conservation_status = trim($_POST["conservation_status"]);
    if (empty($conservation_status)) {
        $conservation_status_err = "Please enter a conservation status.";
    }

    if (isset($_FILES["tree_picture"]) && $_FILES["tree_picture"]["error"] == 0) {
        $targetDir = "../tree_images/"; // Specify the directory where you want to store uploaded files
        $targetFile = $targetDir . basename($_FILES["tree_picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check file size (adjust as needed)
        if ($_FILES["tree_picture"]["size"] > 50000000) {
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
                // Store $targetFile path in a variable to use in database insertion
                $tree_picture = $targetFile;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Check input errors before inserting in database
    if (empty($tree_name_err) && empty($scientific_name_err) && empty($description_err) && empty($habitat_err) && empty($distribution_err) && empty($conservation_status_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO trees (tree_name, scientific_name, description, habitat, distribution, conservation_status, tree_picture) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssss", $tree_name, $scientific_name, $description, $habitat, $distribution, $conservation_status, $tree_picture);
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: treeuniverse_index.php");
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
    <link rel="stylesheet" href="treeuniverse_create.css">
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
                    <p>Please fill this form and submit to add tree record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Tree Name</label>
                            <input type="text" name="tree_name" class="form-control <?php echo (!empty($tree_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tree_name; ?>">
                            <span class="invalid-feedback"><?php echo $tree_name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Scientific Name</label>
                            <input type="text" name="scientific_name" class="form-control <?php echo (!empty($scientific_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $scientific_name; ?>">
                            <span class="invalid-feedback"><?php echo $scientific_name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                            <span class="invalid-feedback"><?php echo $description_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Habitat</label>
                            <input type="text" name="habitat" class="form-control <?php echo (!empty($habitat_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $habitat; ?>">
                            <span class="invalid-feedback"><?php echo $habitat_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Distribution</label>
                            <input type="text" name="distribution" class="form-control <?php echo (!empty($distribution_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $distribution; ?>">
                            <span class="invalid-feedback"><?php echo $distribution_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Conservation Status</label>
                            <input type="text" name="conservation_status" class="form-control <?php echo (!empty($conservation_status_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $conservation_status; ?>">
                            <span class="invalid-feedback"><?php echo $conservation_status_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Tree Picture</label>
                            <input type="file" name="tree_picture" class="form-control-file">
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="treeuniverse_index.php" class="btn btn-secondary ml-2">Cancel</a>
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
