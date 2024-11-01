<?php
// Include necessary configurations and database connection
include '../components/config.php';

// Fetch events data from the database
$sql = "SELECT * FROM events WHERE status = 'active'";
$result = mysqli_query($conn, $sql);

// Initialize an array to hold events
$events = [];

// Check if there are events in the database
if (mysqli_num_rows($result) > 0) {
    // Fetch each row as an associative array
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
} else {
    echo "<p>No events available.</p>";
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="home.css"> 
</head>

<body>

<!--navbar-->
<div class="header">
    <?php include '../components/user_header.php';?>
</div>


<!--about us-->
<section class="content">
    <div class="grid-container">
        <div class="video-box">
            <video controls>
                <source src="../images/napwc_avp.mp4" type="video/mp4"> 
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="text-content">
            <p>The Ninoy Aquino Parks and Wildlife Center (NAPWC) lies in the heart of Quezon City. 
                It serves as an oasis in a highly urbanized environment where various species of flora and fauna can be found. 
                The 22.7-hectare NAPWC is under the management and administration of the Biodiversity Management Bureau (BMB), 
                a staff bureau of the Department of Environment and Natural Resources (DENR), responsible for managing the country’s 
                Protected Area System and providing directions for the conservation of the nation’s biodiversity. Its offices are integrated 
                into the overall landscape of the Park.</p>
            </br>
            <p>The NAPWC envisions being a world-class ecotourism destination and a venue for biodiversity conservation and education 
                on Philippine endemic and rare wild flora and fauna. Its mission is to provide a broad spectrum of outdoor recreational 
                and ecotourism opportunities with areas to play, appreciate nature, and gain delightful experiences. All year round, the 
                Park becomes the venue for various educational, scientific, civil, religious, and recreational activities, as well as orchid shows,
                garden and science fairs, as it provides facilities conducive to such events.</p>
        </div>
    </div>
</section>

<!-- facilities -->
<section class="facilities">
    <h2>Facilities Available</h2>
    <p>Ninoy Aquino Parks and Wildlife Center accepts appointments for special meetings, occasions, gathering, and etc. Kindly review the guidelines and instructions for booking.</p>
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
            <p>Tea House can be used for workshops, seminars, meetings and perfect for intimate gatherings.</p>
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


<!--events-->
<section class="upcoming-events">
    <h2>Upcoming Events</h2>
    <p>Join us for our exciting upcoming events at the Ninoy Aquino Parks and Wildlife Center! 
       Explore nature, learn about biodiversity, and participate in fun activities designed for everyone.</p>
    <div class="carousel">
        <div class="carousel-images">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <div class="event">
                        <img src="<?php echo $event['event_image']; ?>" alt="Event Image" class="event-image">
                        <h3 class="event-title"><?php echo $event['event_name']; ?></h3>
                        <p class="event-description"><?php echo $event['event_description']; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No events available.</p>
            <?php endif; ?>
        </div>
        <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="next" onclick="moveSlide(1)">&#10095;</button>
    </div>
</section>



<footer>
<?php include '../components/phone_footer.php';?>   
</footer>

<script>
// Initialize the current index for the carousel
let currentIndex = 0;

function moveSlide(direction) {
    const carouselImages = document.querySelector('.carousel-images');
    const totalSlides = document.querySelectorAll('.carousel-images .event').length;

    // Update the current index, keeping it within bounds of available slides
    currentIndex = (currentIndex + direction + totalSlides) % totalSlides;

    // Update the transform property to slide the images
    carouselImages.style.transform = 'translateX(' + (-currentIndex * 100) + '%)';
}

// Add event listeners for buttons
document.querySelector('.next').addEventListener('click', () => moveSlide(1));
document.querySelector('.prev').addEventListener('click', () => moveSlide(-1));

</script>

</body>
</html>