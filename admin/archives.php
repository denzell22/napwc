<?php
// Include the configuration file to establish a database connection
include '../components/config.php';

// Retrieve archived booking records
$archivedSql = "SELECT * FROM booking_records WHERE archived = 1";
$archivedResult = $conn->query($archivedSql);

// Check for errors in the query
if (!$archivedResult) {
    die('Query Error: ' . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Archived Booking Records</title>
    <style>
        body {
            font-family: "Montserrat", sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5) url('../images/napwcbg1.jpg') center center fixed;
            background-size: cover;
        }

        .container {
            margin-top: 30px;
            background-color: rgba(255, 255, 255, 0.5); /* Adjust the alpha value for transparency */
            border-radius: 10px;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        .btn-group {
            margin-bottom: 20px;
        }

        #search {
            width: 100%;
            margin-bottom: 20px;
        }

        .table {
            background-color: #fff;
            margin-top: 80px;
            margin-bottom: 200px;
        }

        /* Add some spacing to buttons in the table */
        .table .btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }

    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center alert alert-info">Archived Booking Records</h1>
        <!-- Back Button -->
        <a href="../admin/admin_home.php" class="btn btn-primary">Back to Home</a>
        <!-- Archives button -->
        <a href="../admin/admin.php" class="btn btn-warning">Go to Admin Booking Records</a><br><br>
         <!-- Search Bar Form -->
         <form class="form-inline">
            <div class="form-group">
                <label for="search">Search:</label>
                <input type="text" class="form-control" id="search" name="search" placeholder="Enter name, ID, date, or section">
            </div>
        </form>
        <div class="row">
            <div class="col-md-12">
                <?php
                // Retrieve archived booking records
                $archivedSql = "SELECT * FROM booking_records WHERE archived = 1";
                $archivedResult = $conn->query($archivedSql);

                // Check for errors in the query
                if (!$archivedResult) {
                    die('Query Error: ' . $conn->error);
                }
                ?>
                <?php if ($archivedResult->num_rows > 0): ?>
                    <table class="table table-bordered" id="archivedTable">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Name</th>
                                <th>Last Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Arrival</th>
                                <th>Leave</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($archivedRow = $archivedResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $archivedRow['ID']; ?></td>
                                    <td><?php echo $archivedRow['NAME']; ?></td>
                                    <td><?php echo $archivedRow['lastname']; ?></td>
                                    <td><?php echo $archivedRow['PHONE']; ?></td>
                                    <td><?php echo $archivedRow['EMAIL']; ?></td>
                                    <td><?php echo date('m/d/Y', strtotime($archivedRow['DATE'])); ?></td>
                                    <td><?php echo date('g:i A', strtotime($archivedRow['ARRIVAL_TIME'])); ?></td>
                                    <td><?php echo date('g:i A', strtotime($archivedRow['TIME_OF_LEAVE'])); ?></td>
                                    <td><?php echo $archivedRow['SECTION']; ?></td>
                                    <td><?php echo $archivedRow['CONFIRMATION']; ?></td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-xs restoreBtn" data-id="<?php echo $archivedRow['ID']; ?>">Restore</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No archived booking records found.</p>
                <?php endif; ?>

                <?php
                // Close the connection
                $conn->close();
                ?>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Event delegation for dynamically added elements
            $(document).on("click", ".restoreBtn", function() {
                var bookingId = $(this).data("id");

                // Perform AJAX request to restore the record
                $.ajax({
                    type: "GET",
                    url: "restore_record.php", // Create a new PHP file for handling the restore request
                    data: {
                        id: bookingId
                    },
                    success: function(response) {
                        var alertMessage = "Restored!";
                        alert(alertMessage);
                        // Reload the table after restoration
                        location.reload();
                    }
                });
            });
            $("#search").on("input", function() {
            var searchValue = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "filter_archives.php",
                    data: {
                        search: searchValue
                    },
                    success: function(response) {
                        $("#archivedTable tbody").html(response);
                    }
                });
            });

        });
    </script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>
