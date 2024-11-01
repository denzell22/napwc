<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or any other action you prefer
    header("Location: ../user/home.php");
    exit();
}

include '../components/config.php';

// Function to check and cancel bookings within 3 days of the scheduled date
function cancel_near_dates($conn) {
    $currentDate = date("Y-m-d");
    $threeDaysLater = date("Y-m-d", strtotime($currentDate . ' + 3 days'));

    $sql = "UPDATE booking_records 
            SET CONFIRMATION = 'CANCELLED' 
            WHERE DATE < ? 
            AND CONFIRMATION != 'CONFIRMED' 
            AND archived = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $threeDaysLater);

    if (!$stmt->execute()) {
        die('Execute Error: ' . $stmt->error);
    }
}

// Call the function to check and cancel bookings
cancel_near_dates($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../images/denr.jpg">
    <title>My Appointments</title>
    <link rel="stylesheet" href="myappointments.css">
    
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        @media (max-width: 600px) {
            /* Hide all table headers */
            th {
                display: none;
            }

            /* Make each row a block for better readability */
            tr {
                display: block;
                margin-bottom: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }

            /* Show only Booking ID and Action buttons in mobile view */
            td {
                display: none; /* Hide all cells */
            }

            td.facility, /* Only display booking ID */
            td.action-buttons { /* Only display action buttons */
                display: block;
                width: 80%;
                text-align: center; /* Center align text in mobile view */
                padding: 5px;
                margin: 0 auto; /* Center the element */
            }

            /* Align facility name to the left in mobile view */
            td.facility {
                text-align: left; /* Align facility name to the left */
            }

            /* Ensure booking ID and buttons are displayed as a flexbox for layout */
            tr {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 5px;
                background-color: #f9f9f9;
                cursor: pointer;
            }

            /* Adjust action buttons for mobile */
            .action-buttons {
                text-align: center;
            }

            .action-buttons a {
                font-size: 12px; 
                padding: 4px 8px;
            }

            /* View icon styling for mobile */
            .view-icon {
                display: inline; /* Show view icon in mobile */
                margin-left: 5px;
                cursor: pointer;
                align-self: center; /* Align icon vertically centered */
            }
        }

        /* Hide view icon in desktop mode */
        @media (min-width: 601px) {
            .view-icon {
                display: none; /* Hide view icon in desktop */
            }
        }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }

        /* Close Button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        td.facility {
            display: flex;
            align-items: center; /* Vertically align items */
            justify-content: space-between; /* Distribute space between items */
        }

        .view-icon {
            margin-left: 8px; /* Add some space between facility name and icon */
            cursor: pointer;
            color: #007bff; /* Change color if needed for better visibility */
        }

        .view-icon:hover {
            color: #0056b3; /* Optional: Change color on hover */
        }

        /* Align facility names in a straight line */
        td.facility div {
            display: flex;
            align-items: center;
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="header">
        <?php include '../components/user_header2.php'; ?>
    </div>

    <div class="container">
        <h4>My Appointments</h4>

        <?php
        // Connect to the database
        include '../components/config.php';

        // Retrieve appointments for the logged-in user based on username
        $username = $_SESSION['username'];

        $currentDate = date("Y-m-d"); // Use Y-m-d format for comparison
        
        // Modify the query to match the column name in your database
        $sql = "SELECT br.* FROM booking_records br 
                JOIN user u ON br.NAME = u.first_name 
                WHERE u.username = ? AND br.archived = 0 AND br.DATE >= ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $currentDate);

        if (!$stmt->execute()) {
            die('Execute Error: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        if ($result === false) {
            die('Get Result Error: ' . $stmt->error);
        }
        ?>

        <?php if ($result->num_rows > 0): ?>
            <table>
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
                        <th>Action</th> 
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
                            <td><?php echo date('m/d/Y', strtotime($row['DATE'])); ?></td>
                            <td class="facility">
                                <div>
                                    <?php echo $row['SECTION']; ?>
                                    <i class="fas fa-eye view-icon" data-id="<?php echo $row['ID']; ?>" title="View Details"></i>
                                </div>
                            </td>
                            <td>&#8369;<?php echo $row['PRICE']; ?></td>
                            <td class="confirmationStatus"><?php echo $row['CONFIRMATION']; ?></td>
                            <td class="action-buttons">
                                <?php if ($row['archived'] == 0): ?>
                                    <?php if ($row['CONFIRMATION'] == 'CONFIRMED'): ?>
                                        <a href="#" class="downloadBtn" data-id="<?php echo $row['ID']; ?>">Download Form</a>
                                    <?php elseif ($row['CONFIRMATION'] == 'CANCELLED'): ?>
                                        <a href="#" class="cancelled">Booking Cancelled</a>
                                    <?php else: ?>
                                        <a href="#" class="confirmBtn" data-id="<?php echo $row['ID']; ?>">Confirm</a>
                                        <a href="#" class="cancelBtn" data-id="<?php echo $row['ID']; ?>">Cancel</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="background-color: #6c757d;">Archived</span>
                                <?php endif; ?>
                            </td>
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
    
    <!-- Modal for booking info -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modalBody">
                <!-- Booking details will be injected here -->
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            // Confirm Booking
            $('.confirmBtn').click(function (e) {
                e.preventDefault(); // Prevent default anchor click behavior
                var ID = $(this).data('id');

                // AJAX call to update status to 'CONFIRMED'
                $.ajax({
                    type: "POST",
                    url: "update_booking.php",
                    data: { ID: ID, status: 'CONFIRMED' },
                    success: function (response) {
                        alert('Booking Confirmed for ID: ' + ID);
                        // Reload the page after confirmation
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        alert('Error confirming booking: ' + error);
                    }
                });
            });

            // Cancel Booking
            $('.cancelBtn').click(function (e) {
                e.preventDefault(); // Prevent default anchor click behavior
                var ID = $(this).data('id');

                // Show a confirmation dialog
                if (confirm('Are you sure you want to cancel this booking?')) {
                    // If user confirms, proceed with cancellation
                    $.ajax({
                        type: "POST",
                        url: "update_booking.php",
                        data: { ID: ID, status: 'CANCELLED' },
                        success: function (response) {
                            alert('Booking Cancelled for ID: ' + ID);
                            // Reload the page after cancellation
                            location.reload();
                        },
                        error: function (xhr, status, error) {
                            alert('Error cancelling booking: ' + error);
                        }
                    });
                } else {
                    console.log('Cancellation canceled by user.');
                }
            });

            // Download Booking Information
            $('.downloadBtn').click(function (e) {
                e.preventDefault(); // Prevent default anchor click behavior
                var ID = $(this).data('id');
                window.location.href = "download_booking.php?ID=" + ID;
            });

            // Show Modal with Booking Info
            $('.view-icon').click(function () {
                var ID = $(this).data('id');

                // AJAX call to fetch booking details
                $.ajax({
                    type: "GET",
                    url: "fetch_booking.php", // Create this PHP file to fetch booking details based on ID
                    data: { ID: ID },
                    success: function (response) {
                        $('#modalBody').html(response); // Insert booking details into modal body
                        $('#bookingModal').show(); // Show the modal
                    },
                    error: function (xhr, status, error) {
                        alert('Error fetching booking details: ' + error);
                    }
                });
            });

            // Close Modal
            $('.close').click(function () {
                $('#bookingModal').hide(); // Hide the modal
            });

            // Close modal if the user clicks anywhere outside of it
            $(window).click(function (event) {
                if (event.target === document.getElementById('bookingModal')) {
                    $('#bookingModal').hide(); // Hide the modal
                }
            });
        });
    </script>

    <div class="footer">
        <?php include '../components/phone_footer.php'; ?>
    </div>

</body>
</html>
