<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Map Records</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="map_index.css">

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
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="float-left">Map Records</h2>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="form-inline float-right">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </form>
                        <a href="map_add.php" class="btn btn-success float-right"><i class="fa fa-plus"></i> Add New Record</a>
                    </div>

                    <?php
                        // Include config file
                        require_once "config.php";

                        // Attempt select query execution with search parameter
                        $search = isset($_GET['search']) ? mysqli_real_escape_string($link, $_GET['search']) : '';
                        $sql = "SELECT * FROM map WHERE name LIKE '%$search%' OR category LIKE '%$search%'";

                        if ($result = mysqli_query($link, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                echo '<div class="table-responsive">'; /* Add responsive wrapper */
                                echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>Category</th>";
                                echo "<th>Name</th>";
                                echo "<th>Image</th>";
                                echo "<th>Description</th>";
                                echo "<th>Latitude</th>";
                                echo "<th>Longitude</th>";
                                echo "<th>Action</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['category'] . "</td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>";
                                    echo '<img src="' . $row['image'] . '" class="thumbnail" alt="image">';
                                    echo "</td>";
                                    echo "<td>" . $row['description'] . "</td>";
                                    echo "<td>" . $row['latitude'] . "</td>";
                                    echo "<td>" . $row['longitude'] . "</td>";
                                    echo "<td>";
                                    echo '<a href="map_update.php?id=' . $row['id'] . '" class="mr-3" title="Edit Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                    echo '<a href="map_delete.php?id=' . $row['id'] . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                                echo '</div>'; /* Close the responsive wrapper */
                                // Free result set
                                mysqli_free_result($result);
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }
                        // Close connection
                        mysqli_close($link);
                        ?>

                </div>
            </div>        
        </div>
    </div>
    <div class="footer">
        <?php include '../components/phone_footer.php'; ?>
    </div>
</body>
</html>
