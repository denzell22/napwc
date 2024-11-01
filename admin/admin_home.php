<?php
// Include the database connection file
include '../components/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin_home.css">
    <title>Admin Panel</title>
</head>

<body>

    <div class="page-container">
        <div class="header">
            <?php include '../components/admin_header_loggedin.php'; ?>
        </div>

        <div class="workspace-container">
            <div class="main-workspace">
                <h1 class="text-center">WELCOME TO NINOY AQUINO PARKS AND WILDLIFE CENTER WEBSITE</h1>
                <h2 class="lead text-center">BUREAU OF BIODIVERSITY MANAGEMENT</h2>

                <!-- About us section -->
                <section class="content">
                    <div class="grid-container">
                        <div class="video-box">
                            <video controls>
                                <source src="../images/napwc_avp.mp4" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        <div class="text-content">
                            <p>
                                The Ninoy Aquino Parks and Wildlife Center (NAPWC) lies in the heart of Quezon City.
                                It serves as an oasis in a highly urbanized environment where various species of flora
                                and fauna can be found. The 22.7-hectare NAPWC is under the management and administration
                                of the Biodiversity Management Bureau (BMB), a staff bureau of the Department of
                                Environment and Natural Resources (DENR), responsible for managing the country’s Protected
                                Area System and providing directions for the conservation of the nation’s biodiversity.
                                Its offices are integrated into the overall landscape of the Park.
                            </p>
                            <br>
                            <p>
                                The NAPWC envisions being a world-class ecotourism destination and a venue for biodiversity
                                conservation and education on Philippine endemic and rare wild flora and fauna. Its mission is
                                to provide a broad spectrum of outdoor recreational and ecotourism opportunities with areas to
                                play, appreciate nature, and gain delightful experiences. All year round, the Park becomes the
                                venue for various educational, scientific, civil, religious, and recreational activities, as well
                                as orchid shows, garden and science fairs, as it provides facilities conducive to such events.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Carousel for Events -->
                <div class="events-container">
                    <h2 class="text-center" style="font-size: 30px;">UPCOMING EVENTS</h2>
                    <div id="eventCarousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            // Fetch events data from the database
                            $sql = "SELECT * FROM events WHERE status = 'active'";
                            $result = mysqli_query($conn, $sql);

                            // Check if there are events in the database
                            if (mysqli_num_rows($result) > 0) {
                                $first = true;
                                // Display events in the carousel
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<div class='carousel-item" . ($first ? " active" : "") . "'>";
                                    echo "<h3 class='lead text-center' style='font-size: 30px'>" . $row['event_name'] . "</h3>";
                                    echo "<p class='event-description lead text-center' style='font-size: 20px'>" . $row['event_description'] . "</p>";

                                    // Check if there is an image for the event
                                    if (!empty($row['event_image'])) {
                                        echo "<img src='" . $row['event_image'] . "' alt='Event Image' class='event-image'>";
                                    }

                                    echo "</div>";
                                    $first = false;
                                }
                            } else {
                                echo "<p>No events available.</p>";
                            }

                            // Close the database connection
                            mysqli_close($conn);
                            ?>
                        </div>
                        <a class="carousel-control-prev" href="#eventCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#eventCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <br><hr>

                <!-- Facilities -->
                <section class="facilities">
                    <h2>Facilities Available</h2>
                    <p>
                        Ninoy Aquino Parks and Wildlife Center accepts appointments for special meetings, occasions,
                        gatherings, etc. Kindly review the guidelines and instructions for booking.
                    </p>
                    <div class="facility-list">
                        <div class="facility-box">
                            <a href='https://www.facebook.com/photo/?fbid=3385843308317305&set=a.1680198655548454' target="_blank">
                                <img src="../images/fish villa.jpg" alt="Fishing Village">
                            </a>
                            <h3>Fishing Village</h3>
                            <p>Ideal for wedding receptions and other social functions.</p>
                        </div>
                        <div class="facility-box">
                            <a href='https://www.facebook.com/photo/?fbid=1680200508881602&set=a.1680198655548454' target="_blank">
                                <img src="../images/tea.jpg" alt="Tea House">
                            </a>
                            <h3>Tea House</h3>
                            <p>Tea House can be used for workshops, seminars, meetings, and perfect for intimate gatherings.</p>
                        </div>
                        <div class="facility-box">
                            <a href='https://www.facebook.com/photo/?fbid=2512730855628559&set=a.1680198655548454' target="_blank">
                                <img src="../images/amphi.jpg" alt="Amphitheater">
                            </a>
                            <h3>Amphitheater</h3>
                            <p>An open-air venue that allows you to enjoy an impressive view of the man-made lagoon.</p>
                        </div>
                        <div class="facility-box">
                            <a href='https://www.facebook.com/photo/?fbid=2217785961789718&set=a.1680198655548454' target="_blank">
                                <img src="../images/shed.jpg" alt="Shed">
                            </a>
                            <h3>Picnic Shed</h3>
                            <p>The rental fee for this Picnic Shed costs PHP 500.00 for the whole day.</p>
                        </div>
                    </div>
                </section>

                <div class="footer">
                    <?php include '../components/phone_footer.php'; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
