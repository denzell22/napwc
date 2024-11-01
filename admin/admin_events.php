<?php

// Include the database connection file
include('../components/config.php');

// Fetch active events from the database
$sql = "SELECT * FROM events WHERE status = 'active'";
$result = mysqli_query($conn, $sql);

// Check if there are active events
$events = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="admin_events.css">
    
</head>

<body>

    <div class="page-container">

        <div class="header">
            <?php
            // Include the header.php file
            include '../components/admin_header_loggedin.php';
            ?>
        </div>

        <div class="main-workspace">
            <!-- Button to Add Event -->

            <h2>Event Announcement Manager</h2>

            <!-- Display events -->
            <?php if (!empty($events)) : ?>
                <ul class="event-container">
                    <?php foreach ($events as $event) : ?>
                        <li class="event-item">
                            <h3 class="event-title"><?php echo $event['event_name']; ?></h3>
                            <p class="event-description"><?php echo $event['event_description']; ?></p>
                            <img src="<?php echo $event['event_image']; ?>" alt="Event Image" class="event-image">
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>No events found.</p>
            <?php endif; ?>
        </div>

        <div class="footer">
            <?php
            // Include the footer.php file
            include '../components/footer.php';
            ?>
        </div>

    </div>

</body>

</html>