<?php
session_start();
// Check if the user is not logged in, redirect to the login page if true
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== 'admin')) {
    header("Location: ../user/user_home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="admin.css">
    <title>Admin Panel - Booking Records</title>

</head>

<body>

    <div class="header">
        <?php
        // Include admin header if needed
        include '../components/admin_header_loggedin.php';
        ?>

    </div>

    <div class="container">
        <h1 class="text-center alert alert-info">Admin Panel - Booking Records</h1>
        
        <!-- Pagination at the top -->
        <div class="pagination pagination-top">
            <?php
            // Connect to the database
            include '../components/config.php';

            // Calculate pagination parameters
            $limit = 25; // Number of records per page
            $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
            $offset = ($page - 1) * $limit; // Offset for SQL query

            // Retrieve sorting order from the form or use default (ascending)
            $sortOrder = isset($_GET['sortOrder']) && ($_GET['sortOrder'] === 'desc') ? 'DESC' : 'ASC';

            // Retrieve all booking records with the specified sorting order and pagination
            $sql = "SELECT * FROM booking_records WHERE archived = 0 ORDER BY DATE $sortOrder LIMIT $limit OFFSET $offset";
            $result = $conn->query($sql);

            // Check for errors in the query
            if (!$result) {
                die('Query Error: ' . $conn->error);
            }

            // Calculate total number of pages
            $total_pages_sql = "SELECT COUNT(*) FROM booking_records WHERE archived = 0";
            $result_total = $conn->query($total_pages_sql);
            $total_rows = $result_total->fetch_array()[0];
            $total_pages = ceil($total_rows / $limit);
            ?>
        <!-- Search Bar Form -->
        <form class="form-inline">
            <div class="form-group">
                <input type="text" class="form-control" id="search" name="search" placeholder="Search Name, ID, Date, or Section">
                <button type="submit" class="btn btn-primary">Sort</button>
                <select class="form-control" id="sortOrder" name="sortOrder">
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>
        </form></br>

        <!-- Dropdown Button for Time Frame Selector -->
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Download Records <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#" class="time-frame-option" data-time-frame="all">All Records</a></li>
                <li><a href="#" class="time-frame-option" data-time-frame="daily">Daily Records</a></li>
                <li><a href="#" class="time-frame-option" data-time-frame="weekly">Weekly Records</a></li>
                <li><a href="#" class="time-frame-option" data-time-frame="monthly">Monthly Records</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php if ($result->num_rows > 0): ?>
                    <table class="table table-bordered" id="bookingTable">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Name</th>
                                <th>Last Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Section</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['ID']; ?></td>
                                    <td><?php echo $row['NAME']; ?></td>
                                    <td><?php echo $row['lastname']; ?></td>
                                    <td><?php echo $row['PHONE']; ?></td>
                                    <td><?php echo $row['EMAIL']; ?></td>
                                    <td><?php echo date(($sortOrder === 'desc') ? 'm/d/Y' : 'Y-m-d', strtotime($row['DATE'])); ?></td>
                                    <td><?php echo $row['SECTION']; ?></td>
                                    <td>&#8369;<?php echo $row['PRICE']; ?></td>
                                    <td><?php echo $row['CONFIRMATION']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No booking records found.</p>
                <?php endif; ?>

                <?php
                // Close the connection
                $conn->close();
                ?>
            </div>
        </div>

        <!-- Pagination at the bottom -->
        <div class="pagination">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php
                    // Generate pagination links
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<li class='page-item'><a class='page-link' href='admin.php?page=$i'>$i</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Event listener for input changes in the search bar
            $("#search").on("input", function () {
                var searchValue = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "filter_records.php",
                    data: {
                        search: searchValue
                    },
                    success: function (response) {
                        $("#bookingTable tbody").html(response);
                    }
                });
            });

            // Event listener for time frame selection
            $(".time-frame-option").on("click", function (e) {
                e.preventDefault();
                var timeFrame = $(this).data("time-frame");
                // Open the PDF generation script with the selected time frame
                window.open("generate_pdf.php?time_frame=" + timeFrame, "_blank");
            });
        });
    </script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>