<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Montserrat", sans-serif;
        }

        /* Header styles */
        .navbar {
            background-color: #333;
            color: white;
            padding: 20px;
            height: 200px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative; /* Position relative for dropdown */
        }

        .navbar img {
            height: 100px; /* Further reduced logo height */
            border-radius: 20px;
        }

        /* Park name */
        .park-name {
            font-size: 25px;
            font-weight: bold;
            color: #fff;
            margin-left: 50px; /* Reduced margin to bring the name closer to the logo */
            flex-grow: 1;
        }

        /* Navigation menu */
        .nav-menu {
            display: flex;
            gap: 10px; /* Space between buttons */
            margin-right: 5px; /* Reduced space between buttons and name */
        }

        .nav-menu a,
        .nav-menu button {
            display: flex; /* Use flex to ensure the content is centered */
            justify-content: center; /* Center text horizontally */
            align-items: center; /* Center text vertically */
            text-decoration: none;
            padding: 10px 15px;
            background-color: #4C6444;
            color: #FBFADA;
            font-size: 14px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            height: 40px; /* Set a fixed height */
            width: 100px; /* Set a fixed width for all buttons */
        }

        .nav-menu a:hover,
        .nav-menu button:hover {
            background-color: #B68D40;
            color: white;
        }

        /* Burger icon */
        .burger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .burger div {
            width: 20px;
            height: 1.5px;
            background-color: #FBFADA;
            margin: 3px 0;
            transition: 0.4s;
        }

        /* Popup menu */
        .popup {
            width: 310px;
            height: 30px;
            display: none; /* Initially hidden */
            flex-direction: row; /* Align items horizontally */
            position: absolute;
            margin-left: 50px; /* Align with the right edge of the burger */
            background-color: #4C6444;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1; /* Ensure it appears above other content */
            transform: translateX(-100%); /* Start off-screen to the left */
            transition: transform 0.5s ease; /* Smooth transition */
        }

        .popup.show {
            display: flex; /* Show popup when active */
            transform: translateX(0); /* Slide in */
        }

        .popup a {
            margin: 0 9px; /* Space between items */
            color: #fff;
            font-size: 8px;
            text-align: center; /* Center text horizontally */
        }

        /* Responsive design for mobile */
        @media only screen and (max-width: 768px) {
            .navbar {
                flex-direction: column; /* Stack elements vertically */
                padding: 8px 15px;
                align-items: center; /* Align items to the center */
            }

            .navbar img {
            height: 50%; /* Further reduced logo height */
            border-radius: 20px;
            margin-bottom: 20px;
            }

            .park-name {
            font-size: 15px;
            font-weight: bold;
            margin-left: 10px;
            }

            .nav-menu {
                display: none; /* Hide the original nav menu */
            }

            .burger {
                display: flex; /* Show burger icon */
                align-self: flex-start; /* Align burger to the top */
                justify-content: flex-end; /* Align the icon to the right horizontally */
                margin-top: 5px; /* Distance from the top of the container */
                right: 5px; /* Distance from the right of the container */
            }

            .popup {
                top: 165px; /* Adjust as needed for spacing */
            }

        }
    </style>
</head>
<body>

    <div class="navbar">
        <img src="../images/napwc_logo.jpg" alt="Logo">
        <h1 class="park-name">Ninoy Aquino Parks and Wildlife Center</h1>
        <div class="burger" id="burger" onclick="togglePopup()">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <nav class="nav-menu" id="navMenu">
            <a href="../user/homepage_logged.php">Home</a>
            <a href="../user/messaging.php">Contact Us</a>
            <a href="../user/booking_home.php">Appointments</a>
            <a href="../user/map_loggedin.php">Map</a>
            <a href="../user/treeuniv_logged.php">Explore</a>
            <a href="../user/home.php">Log Out</a>
        </nav>
    </div>


<!-- Popup menu -->
<div class="popup" id="popupMenu">
    <a href="../user/homepage_logged.php">Home</a>
    <a href="../user/messaging.php">Contact Us</a>
    <a href="../user/booking_home.php">Appointments</a>
    <a href="../user/map_loggedin.php">Map</a>
    <a href="../user/treeuniv_logged.php">Explore</a>
    <a href="../user/home.php">Log Out</a>
</div>

<script>
    function togglePopup() {
    const popup = document.getElementById('popupMenu');
    // Toggle 'show' class
    if (popup.classList.contains('show')) {
        popup.classList.remove('show'); // Hide the popup
        // Use setTimeout to ensure the popup is hidden after the transition
        setTimeout(() => {
            popup.style.display = 'none'; // Set display to none after hiding
        }, 300); // Match the duration of the transition
    } else {
        popup.style.display = 'flex'; // Show the popup
        setTimeout(() => {
            popup.classList.add('show'); // Add the show class to slide in
        }, 10); // Small timeout to allow the display to take effect
    }
    }

    // Close the popup if the user clicks outside of it
    window.onclick = function(event) {
    const popup = document.getElementById('popupMenu');
    if (!event.target.matches('.burger') && !event.target.matches('.burger div')) {
        popup.classList.remove('show'); // Hide the popup
        setTimeout(() => {
            popup.style.display = 'none'; // Set display to none after hiding
        }, 300); // Match the duration of the transition
    }
    }
</script>
</body>
</html>
