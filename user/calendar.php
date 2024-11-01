<?php
// Include necessary configurations and database connection
include '../components/config.php';

// Fetch facilities from the database
$sql = "SELECT facility_name, PRICING, maxpax FROM facilities";
$result = $conn->query($sql);
$facilities = [];

if ($result->num_rows > 0) {
    // Store facilities in an array
    while($row = $result->fetch_assoc()) {
        $facilities[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Reservation</title>
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="calendar.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="header">
        <?php include '../components/user_header2.php'; ?>
    </div>

    <div class="calendar-container">
        <header class="calendar-bg">
            <button id="prev" class="nav-button">Prev</button>
            <h2 id="month-year"></h2>
            <button id="next" class="nav-button">Next</button>
        </header>
        <div class="calendar">
            <div class="day-names">
                <div>Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>
            </div>
            <div id="days" class="days"></div>
        </div>
    </div>

    <!-- Popup Modal for Booking Not Allowed -->
    <div class="modal fade" id="bookingNotAllowedModal" tabindex="-1" role="dialog" aria-labelledby="bookingNotAllowedModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingNotAllowedModalLabel">Booking Not Allowed</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Booking is not allowed for this date. Please select another date within the allowed booking range.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup Modal for Booking Facilities -->
    <div class="modal fade" id="facilityModal" tabindex="-1" role="dialog" aria-labelledby="facilityModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="facilityModalLabel">Available Facilities</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="facility-list" class="facility-list">
                        <!-- Facilities will be populated here by JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
const monthYearDisplay = document.getElementById('month-year');
const daysContainer = document.getElementById('days');
const prevButton = document.getElementById('prev');
const nextButton = document.getElementById('next');

// Facilities data from PHP
const facilities = <?php echo json_encode($facilities); ?>;

let currentDate = new Date();

function renderCalendar() {
    currentDate.setDate(1); // Set to the first day of the month

    const month = currentDate.getMonth();
    const year = currentDate.getFullYear();

    const firstDayIndex = currentDate.getDay(); // Day of the week the month starts on
    const lastDay = new Date(year, month + 1, 0).getDate(); // Last date of the month

    const today = new Date(); // Get today's date (for comparison)
    today.setHours(0, 0, 0, 0); // Ensure time isn't compared

    // Calculate the restricted date (three days from today)
    const minBookingDate = new Date(today);
    minBookingDate.setDate(today.getDate() + 3);

    monthYearDisplay.textContent = `${currentDate.toLocaleString('default', { month: 'long' })} ${year}`;

    daysContainer.innerHTML = ''; // Clear previous days

    // Empty boxes for days before the first day of the month
    for (let i = 0; i < firstDayIndex; i++) {
        daysContainer.innerHTML += `<div class="day empty"></div>`;
    }

    // Render each day of the month
    for (let day = 1; day <= lastDay; day++) {
        const dayDate = new Date(year, month, day);

        const isToday = dayDate.getTime() === today.getTime(); // Check if it's today's date

        // Restrict dates within the next 3 days
        const isRestricted = dayDate < minBookingDate;

        // Add the day to the calendar with the appropriate classes
        daysContainer.innerHTML += `
            <div class="day ${isToday ? 'today' : ''} ${isRestricted ? 'restricted' : ''}"
                 data-restricted="${isRestricted}" 
                 data-day="${day}">
                ${day}
            </div>
        `;
    }

    // Add event listeners to days
    const dayElements = document.querySelectorAll('.day:not(.empty)'); // Exclude empty days
    dayElements.forEach(dayElement => {
        dayElement.addEventListener('click', () => {
            const selectedDay = dayElement.getAttribute('data-day');
            const isRestricted = dayElement.getAttribute('data-restricted') === 'true';

            // Show modal for allowed dates
            if (!isRestricted) {
                showFacilities();
            } else {
                $('#bookingNotAllowedModal').modal('show');
            }
        });
    });
}

function showFacilities() {
    const facilityListDiv = document.getElementById('facility-list');
    facilityListDiv.innerHTML = ''; // Clear previous facilities

    // Populate the facility list
    facilities.forEach(facility => {
        facilityListDiv.innerHTML += `
            <div class='facility-row'>
                <span><strong>${facility.facility_name}</strong></span>
                <span> - Price: ₱ ${parseFloat(facility.PRICING).toFixed(2)}</span>
                <span> - Max Capacity: ${facility.maxpax} persons</span>
            </div>
        `;
    });

    // Show the modal
    $('#facilityModal').modal('show');
}

prevButton.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});

nextButton.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});

// Initial render
renderCalendar();
    </script>

    <div class="header">
        <?php include '../components/phone_footer.php'; ?>
    </div>

</body>
</html>
