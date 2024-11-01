<?php
// Include necessary configurations and database connection
include '../components/config.php';

// Get the facility name from the URL query parameter
$selectedFacility = isset($_GET['facility']) ? $_GET['facility'] : '';


// Fetch booked dates for each facility
$bookedDates = [];
$facilities = ["Fishing Village", "Tea House", "Amphitheater", "Shed 1", "Shed 2", "Shed 3", "Shed 4", "Shed 5"];

foreach ($facilities as $facility) {
    $stmt = $conn->prepare("SELECT DATE FROM booking_records WHERE SECTION = ? AND CONFIRMATION = 'confirmed'");
    $stmt->bind_param('s', $facility);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $bookedDates[$facility][] = $row['DATE'];
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Facility</title>
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="booking_home.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> <!-- Flatpickr CSS -->

    <style>
        .past-date {
            background-color: gray; /* Style for past dates */
            color: white; /* Optional: change text color for better visibility */
        }

        .flatpickr-day.today {
            background-color: #ffcc00; /* Yellow highlight for today */
            color: white; /* Text color for contrast */
            border-radius: 50%; /* Round shape */
        }

        .restricted {
            background-color: red; /* Style for restricted dates */
            color: white; /* Optional: change text color for better visibility */
        }
    </style>
</head>

<body>

<!-- Navbar -->
<div class="header">
    <?php include '../components/user_header2.php'; ?>
</div>

<!-- Facilities Section -->
<section class="facilities">
    <h2>Facilities Available</h2>
    <p>Ninoy Aquino Parks and Wildlife Center accepts appointments for special meetings, occasions, gatherings, etc. Kindly review the guidelines and instructions for booking.</p>
    
    <div class="facility-list">
        <!-- Fishing Village -->
        <div class="facility-box">
            <a href='https://www.facebook.com/photo/?fbid=3385843308317305&set=a.1680198655548454' target="_blank">
                <img src="../images/fish villa.jpg" alt="Fishing Village">
            </a>
            <h3>Fishing Village</h3>
            <p>Ideal for wedding receptions and other social functions.</p>
            <p>Price: ₱ 2,500.00</p>
            <p>Max Capacity: 150 persons</p>
            <button class="book-button" data-facility="Fishing Village" data-price="2500">Book Now</button>
        </div>

        <!-- Tea House -->
        <div class="facility-box">
            <a href='https://www.facebook.com/photo/?fbid=1680200508881602&set=a.1680198655548454' target="_blank">
                <img src="../images/tea.jpg" alt="Tea House">
            </a>
            <h3>Tea House</h3>
            <p>Tea House can be used for workshops, seminars, meetings, and perfect for intimate gatherings.</p>
            <p>Price: ₱ 500.00</p>
            <p>Max Capacity: 50 persons</p>
            <button class="book-button" data-facility="Tea House" data-price="500">Book Now</button>
        </div>

        <!-- Amphitheater -->
        <div class="facility-box">
            <a href='https://www.facebook.com/photo/?fbid=2512730855628559&set=a.1680198655548454' target="_blank">
                <img src="../images/amphi.jpg" alt="Amphitheater">
            </a>
            <h3>Amphitheater</h3>
            <p>An open-air venue that allows you to enjoy an impressive view of the man-made lagoon.</p>
            <p>Price: ₱ 1,350.00</p>
            <p>Max Capacity: 100 persons</p>
            <button class="book-button" data-facility="Amphitheater" data-price="1350">Book Now</button>
        </div>

        <!-- Picnic Sheds -->
        <?php for ($i = 1; $i <= 5; $i++): ?>
        <div class="facility-box">
            <a href='https://www.facebook.com/photo/?fbid=2217785961789718&set=a.1680198655548454' target="_blank">
                <img src="../images/shed.jpg" alt="Shed <?php echo $i; ?>">
            </a>
            <h3>Shed <?php echo $i; ?></h3>
            <p>Picnic Shed available for park visitors to use as shelter during their stay at the park.</p>
            <p>Price: ₱ 500.00</p>
            <p>Max Capacity: 5 persons</p>
            <button class="book-button" data-facility="Shed <?php echo $i; ?>" data-price="500">Book Now</button>
        </div>
        <?php endfor; ?>
    </div>
</section>

<!-- Appointment Buttons -->
<div class="appointment-buttons">
    <a href="myappointments.php" class="my-appointments">
        <i class="fas fa-calendar-check"></i> My Appointments
    </a>
</div>

<!-- Calendar Modal -->
<div id="calendar-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Select a Booking Date</h3>
        <input type="text" id="calendar" placeholder="Select a date">
        <button id="confirm-booking">Confirm Booking</button>
    </div>
</div>

<!-- Hidden Form for Booking Submission -->
<form id="bookingForm" action="book_process.php" method="GET" style="display: none;">
    <input type="hidden" name="facility_name" id="facilityName">
    <input type="hidden" name="date" id="selectedDate">
    <input type="hidden" name="facility_price" id="facilityPrice">
    <input type="hidden" name="max_capacity" id="maxCapacity">
</form>

<footer>
    <?php include '../components/phone_footer.php'; ?>   
</footer>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> <!-- Flatpickr JS -->

<script>
    // Get today's date
    const today = new Date();

    // Set the minimum date for booking to 3 days ahead
    const minBookingDate = new Date();
    minBookingDate.setDate(today.getDate() + 3); // 3 days ahead

    // Array holding restricted dates by facility
const restrictedDates = <?php echo json_encode($bookedDates); ?>;

// Function to get restricted dates for the selected facility
function getRestrictedDatesForFacility(facility) {
    return restrictedDates[facility] || []; // Return dates or an empty array if none exist
}

// Highlight today's date
function highlightToday() {
    const todayDate = today.getDate();
    const todayMonth = today.getMonth();
    const todayYear = today.getFullYear();
    const todayElement = document.querySelector(`.flatpickr-day[data-date="${new Date(todayYear, todayMonth, todayDate).getTime()}"]`);
    if (todayElement) {
        todayElement.classList.add("today");
    }
}

// Modal functionality for booking
const calendarModal = document.getElementById("calendar-modal");

// Handling the booking process for each facility
document.querySelectorAll(".book-button").forEach(button => {
    button.addEventListener("click", () => {
        const facility = button.getAttribute("data-facility");
        const facilityPrice = button.getAttribute("data-price");
        
        // Set the facility and price in the modal form
        document.getElementById("facilityName").value = facility;
        document.getElementById("facilityPrice").value = facilityPrice;

        // Get restricted dates for the selected facility
        const facilityRestrictedDates = getRestrictedDatesForFacility(facility);

        // Initialize Flatpickr for the selected facility
        flatpickr("#calendar", {
            minDate: new Date().fp_incr(3), // Set minimum date to 3 days ahead
            enableTime: false,
            dateFormat: "Y-m-d",
            onReady: function(selectedDates, dateStr, instance) {
                highlightToday(); // Highlight today's date
                
                // Highlight restricted dates for the specific facility
                facilityRestrictedDates.forEach(date => {
                    const restrictedElement = document.querySelector(`.flatpickr-day[data-date="${new Date(date).getTime()}"]`);
                    if (restrictedElement) {
                        restrictedElement.classList.add("restricted"); // Highlight the restricted date
                    }
                });
            },
            onChange: function(selectedDates, dateStr, instance) {
                // Check if the selected date is restricted
                if (facilityRestrictedDates.includes(dateStr)) {
                    alert("You cannot book " + facility + " on this day.");
                }
            }
        });

        // Open the modal for date selection
        calendarModal.style.display = "block";
    });
});

// Confirm booking logic
document.getElementById("confirm-booking").addEventListener("click", () => {
    const selectedDate = document.getElementById("calendar").value;
    const facility = document.getElementById("facilityName").value;

    const facilityRestrictedDates = getRestrictedDatesForFacility(facility);

    // Check if the selected date is restricted for the selected facility
    if (facilityRestrictedDates.includes(selectedDate)) {
        alert("You cannot book " + facility + " on this day.");
    } else {
        // If not restricted, submit the form
        document.getElementById("selectedDate").value = selectedDate;
        document.getElementById("bookingForm").submit();
    }
});

// Close modal when clicking on the close button
document.querySelector(".close").onclick = function() {
    calendarModal.style.display = "none";
};

// Close modal when clicking outside the modal content
window.onclick = function(event) {
    if (event.target == calendarModal) {
        calendarModal.style.display = "none";
    }
};


    window.onclick = function(event) {
        if (event.target == calendarModal) {
            calendarModal.style.display = "none";
        }
    };
</script>


</body>
</html>
