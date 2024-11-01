<!DOCTYPE html>
<html>
<head>
    <title>Ninoy Aquino Parks and Wildlife Center Map</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="../user/map_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"> <!-- Montserrat font -->
    <style>
        
    </style>
</head>
<body>
    <div class="header">
        <?php include '../components/user_header2.php'; ?>
    </div>

    <div class="page-container">
        <div class="container">
        <div class="sidebar">
            <ul class="button-list">
                <li class="category-header">General</li>
                <li><button onclick="highlightArea('park')">Highlight Park Area</button></li>
                <li><button onclick="showUserLocation()">Show My Location</button></li>
                <li><button onclick="clearMarkerAndRoute()">Clear Marker and Route</button></li>
            </ul>

            <!-- Search Section -->
            <div id="search-container">
                <h3>Search Markers</h3>
                <input type="text" id="markerSearch" placeholder="Search for markers...">
                <button id="searchButton" onclick="searchMarkers()">Search</button>

                <!-- Search results list -->
                <ul id="search-results-list"></ul> <!-- New list for search results -->
            </div>

            <!-- Nearby Waypoints Section -->
            <div id="waypoints-container">
                <h3>Nearby Waypoints</h3>
                <ul id="waypoints-list"></ul> <!-- Waypoints list for nearby markers -->
            </div>
        </div>

            <div id="sidebarmini">
                <div id="directions-container"></div>
            </div>
            <div id="map"></div>
        </div>
    </div>
    
    <div class="footer">
        <?php include '../components/phone_footer.php'; ?>
    </div>

    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <script>
        let map, marker, circle, polyline, routingControl, currentPosition, taxiIcon;
        let userMarker, isUserLocationVisible = false;
        let lastUserLat, lastUserLon; // Store user's last known location
        let waypoints = []; // Initialize waypoints array
        let routingMarker; // Initialize the routing marker

        const redIcon = L.divIcon({
        className: 'user-location-icon', // Custom class for the icon
        html: '<div style="background-color: red; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white;"></div>', // HTML for the icon
        iconSize: [20, 20] // Size of the icon
    })

        // Initialize the map
        map = L.map('map').setView([14.65070, 121.04398], 16);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        map.setMaxZoom(18);

        // Taxi icon for routing
        taxiIcon = L.icon({
            iconUrl: '../images/taxi.png',
            iconSize: [30, 30]
        });

         // Initialize routing marker
        routingMarker = L.marker([28.2380, 83.9956], { icon: taxiIcon }).addTo(map);

        // Set up park polygon
        const parkCoordinates = [
            [14.65293, 121.04150], [14.65197, 121.04588], [14.65152, 121.04631],
            [14.65045, 121.04664], [14.65009, 121.04656], [14.64828, 121.04352],
            [14.64839, 121.04331], [14.64839, 121.04302], [14.64858, 121.04279],
            [14.64848, 121.04249], [14.64851, 121.04239], [14.64907, 121.04214],
            [14.65213, 121.04105]
        ];
        const parkPolygon = L.polygon(parkCoordinates, { color: 'green', fillColor: 'green', fillOpacity: 0.3 }).addTo(map);

        // Modify the highlightArea function
        function highlightArea(area) {
            if (area === 'park') {
                if (isParkHighlighted) {
                    // If already highlighted, remove the polygon
                    map.removeLayer(parkPolygon);
                    isParkHighlighted = false; // Update the state
                } else {
                    // If not highlighted, add the polygon
                    parkPolygon.addTo(map);
                    isParkHighlighted = true; // Update the state
                }
            }
        }

    // Initialize markers from the database
    let markers = <?= json_encode(getMarkersFromDatabase()); ?>; // PHP function call to get markers
    markers.forEach(function(markerData) {
        let popupContent = `
            <div class="popup-content">
                <strong>${markerData.name}</strong><br>
                <span>Category: ${markerData.category}</span><br>
                <p class="popup-description">${markerData.description}</p><br>
                <img src="${markerData.image}" alt="${markerData.name} Image" class="popup-image"><br>
            </div>
        `;

        // Only show the booking button if the category is 'facilities'
        if (markerData.category === 'facilities') {
            popupContent += `<button class="popup-button" onclick="redirectToBooking()">Go to Booking Page</button>`;
        }

        // Create the marker with the generated popup content
        let markerInstance = L.marker([markerData.latitude, markerData.longitude])
            .bindPopup(popupContent)
            .bindTooltip(markerData.name, { // Bind the tooltip for hover events
                permanent: false, // Tooltip will only show on hover
                direction: 'top'  // Tooltip will appear above the marker
            })
            .addTo(map);
            
            // Store the marker instance in the waypoints array
            waypoints.push({
                name: markerData.name,
                lat: markerData.latitude,
                lon: markerData.longitude,
                markerInstance: markerInstance
            });
        });

    // Set the map view to Ninoy Aquino Parks and Wildlife Center's location on page load
    map.setView([14.65070, 121.04398], 17);

        // Define the distance function
        function getDistance(lat1, lon1, lat2, lon2) {
            const R = 6371000; // Radius of the Earth in meters
            const dLat = (lat2 - lat1) * (Math.PI / 180);
            const dLon = (lon2 - lon1) * (Math.PI / 180);
            const a = 
                0.5 - Math.cos(dLat) / 2 +
                Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
                (1 - Math.cos(dLon)) / 2;
            return R * 2 * Math.asin(Math.sqrt(a));
        }

       // Function to show nearby waypoints and update sidebar
function showNearbyWaypoints(userLat, userLon, maxDistance) {
    const listElement = document.getElementById('waypoints-list');
    listElement.innerHTML = ''; // Clear the list before adding new waypoints

    let waypointsFound = false; // Track if there are any nearby waypoints
    const nearbyWaypoints = []; // Array to store nearby waypoints with distances

    waypoints.forEach(waypoint => {
        const distance = getDistance(userLat, userLon, waypoint.lat, waypoint.lon);

        if (distance <= maxDistance) {
            waypointsFound = true; // Mark that a nearby waypoint was found

            // Store waypoint and its distance in the array
            nearbyWaypoints.push({ waypoint, distance });
        } else {
            // Remove the marker if it's out of the desired range
            if (map.hasLayer(waypoint.markerInstance)) {
                map.removeLayer(waypoint.markerInstance);
            }
        }
    });

    // Sort the nearby waypoints by distance (nearest first)
    nearbyWaypoints.sort((a, b) => a.distance - b.distance);

    // Add sorted waypoints to the sidebar list
    nearbyWaypoints.forEach(({ waypoint, distance }) => {
        // Add waypoint to the sidebar list
        const listItem = document.createElement('li');
        listItem.textContent = `${waypoint.name} (${distance.toFixed(0)} meters)`;
        listElement.appendChild(listItem);

        // Add the waypoint marker to the map
        waypoint.markerInstance.addTo(map);

        // Add click event to show route to this waypoint
        listItem.addEventListener('click', function () {
            map.setView([waypoint.lat, waypoint.lon], 16); // Zoom to marker
            waypoint.markerInstance.openPopup(); // Open the popup of the marker
            startRouting([userLat, userLon], [waypoint.lat, waypoint.lon]);
        });
    });

    // Check if waypoints were found, then display or hide the list
    if (waypointsFound) {
        listElement.style.display = 'block'; // Show the nearby waypoints list if waypoints are found
    } else {
        listElement.style.display = 'none'; // Hide the nearby waypoints list if no waypoints are found
    }
}



        function showUserLocation() {
            const btn = document.getElementById('showUserLocation');
            if (isUserLocationVisible) {
                // Hide the user location
                if (userMarker) {
                    map.removeLayer(userMarker);
                }
                if (circle) {
                    map.removeLayer(circle);
                }
                isUserLocationVisible = false;
                btn.textContent = "Show My Location";
            } else {
                // Show the user location
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        lastUserLat = position.coords.latitude;
                        lastUserLon = position.coords.longitude;
                        currentPosition = [lastUserLat, lastUserLon];

                        // Add marker for user location
                        userMarker = L.marker(currentPosition, { icon: redIcon, draggable: true }).addTo(map)
                            .on('dragend', function(event) {
                                const draggedMarker = event.target;
                                const position = draggedMarker.getLatLng();
                                lastUserLat = position.lat;
                                lastUserLon = position.lng;
                                currentPosition = [lastUserLat, lastUserLon];

                                // Show nearby waypoints based on the new position
                                showNearbyWaypoints(lastUserLat, lastUserLon, 300);
                            });

                        // Add a circle around the user's location
                        circle = L.circle(currentPosition, { radius: 100, color: 'blue', fillOpacity: 0.1 }).addTo(map);

                        // Center the map on user location
                        map.setView(currentPosition, 16);
                        showNearbyWaypoints(lastUserLat, lastUserLon, 150); // Update nearby waypoints
                        isUserLocationVisible = true;
                        btn.textContent = "Hide My Location";
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            }
        }

        // Clear only the routing elements on the map
function clearMarkerAndRoute() {
    // Remove the routing marker (taxi icon) if it exists
    if (routingMarker) {
        map.removeLayer(routingMarker);
        routingMarker = null; // Clear the reference
    }

    // Remove the routing control if it exists
    if (routingControl) {
        map.removeControl(routingControl);
        routingControl = null; // Clear the reference
    }

    // Remove the polyline if it exists
    if (polyline) {
        map.removeLayer(polyline);
        polyline = null; // Clear the reference
    }

    // Close popups for all nearby waypoints
    waypoints.forEach(waypoint => {
        if (waypoint.markerInstance) {
            waypoint.markerInstance.closePopup(); // Close the popup if it's open
        }
    });
}



function startRouting(start, end) {
    if (routingControl) {
        map.removeControl(routingControl); // Remove any existing routing control
    }

    if (polyline) {
        map.removeLayer(polyline); // Remove any existing polyline
    }

    // Check if both start and end points are strictly inside the park polygon
    const isInsidePark = parkPolygon.contains(L.latLng(start)) && parkPolygon.contains(L.latLng(end));

    if (isInsidePark) {
        // Create a dashed line between points if both are inside the park
        polyline = L.polyline([start, end], { 
            color: 'blue', 
            weight: 3, 
            dashArray: '10, 10' // Creates a dashed line
        }).addTo(map);
        
        routingMarker.setLatLng(start); // Set the initial position of the marker
        map.addLayer(routingMarker); // Make sure the marker is added to the map

        let step = 0;

        // Simulate movement along the line
        const interval = setInterval(() => {
            if (step >= 1) {
                clearInterval(interval); // Stop animation when we reach the end
            } else {
                const currentLat = start[0] + step * (end[0] - start[0]);
                const currentLng = start[1] + step * (end[1] - start[1]);
                routingMarker.setLatLng([currentLat, currentLng]); // Move the marker
                step += 0.01; // Adjust speed of movement
                console.log(`Moving marker to: [${currentLat}, ${currentLng}]`); // Debugging line
            }
        }, 100); // Adjust speed as necessary
    } else {
        // Call displayRoute to handle routing outside the park
        displayRoute(start, end, function() {
            // Ask for confirmation to start live routing after the route is displayed
            if (confirm("Start live routing?")) {
                routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(start[0], start[1]), // Start at the current position
                        L.latLng(end[0], end[1]) // End at the clicked marker's position
                    ],
                    createMarker: function() { return null; } // Prevent routing markers
                }).on('routesfound', function(e) {
                    var routes = e.routes;
                    console.log(routes);

                    // Animate marker along the route
                    var routeCoordinates = routes[0].coordinates.map(coord => [coord.lat, coord.lng]);
                    let index = 0; // Initialize index to track the current position

                    const moveMarker = () => {
                        if (index < routeCoordinates.length) {
                            routingMarker.setLatLng(routeCoordinates[index]); // Move the marker
                            console.log(`Moving marker to: ${routeCoordinates[index]}`); // Debugging line
                            index++;
                            setTimeout(moveMarker, 100); // Adjust speed as necessary
                        }
                    };

                    moveMarker(); // Start moving the marker
                }).addTo(map);
            }
        });
    }
}
function startRouting(start, end) {
    if (routingControl) {
        map.removeControl(routingControl); // Remove any existing routing control
    }

    if (polyline) {
        map.removeLayer(polyline); // Remove any existing polyline
    }

    // Check if both start and end points are strictly inside the park polygon
    const isInsidePark = parkPolygon.contains(L.latLng(start)) && parkPolygon.contains(L.latLng(end));

    if (isInsidePark) {
        // Create a dashed line between points if both are inside the park
        polyline = L.polyline([start, end], { 
            color: 'blue', 
            weight: 3, 
            dashArray: '10, 10' // Creates a dashed line
        }).addTo(map);
        
        routingMarker.setLatLng(start); // Set the initial position of the marker
        map.addLayer(routingMarker); // Make sure the marker is added to the map

        let step = 0;

        // Simulate movement along the line
        const interval = setInterval(() => {
            if (step >= 1) {
                clearInterval(interval); // Stop animation when we reach the end
            } else {
                const currentLat = start[0] + step * (end[0] - start[0]);
                const currentLng = start[1] + step * (end[1] - start[1]);
                routingMarker.setLatLng([currentLat, currentLng]); // Move the marker
                step += 0.01; // Adjust speed of movement
                console.log(`Moving marker to: [${currentLat}, ${currentLng}]`); // Debugging line
            }
        }, 100); // Adjust speed as necessary
    } else {
        // Call displayRoute to handle routing outside the park
        displayRoute(start, end, function() {
            // Ask for confirmation to start live routing after the route is displayed
            if (confirm("Start live routing?")) {
                routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(start[0], start[1]), // Start at the current position
                        L.latLng(end[0], end[1]) // End at the clicked marker's position
                    ],
                    createMarker: function() { return null; } // Prevent routing markers
                }).on('routesfound', function(e) {
                    var routes = e.routes;
                    console.log(routes);

                    // Animate marker along the route
                    var routeCoordinates = routes[0].coordinates.map(coord => [coord.lat, coord.lng]);
                    let index = 0; // Initialize index to track the current position

                    const moveMarker = () => {
                        if (index < routeCoordinates.length) {
                            routingMarker.setLatLng(routeCoordinates[index]); // Move the marker
                            console.log(`Moving marker to: ${routeCoordinates[index]}`); // Debugging line
                            index++;
                            setTimeout(moveMarker, 100); // Adjust speed as necessary
                        }
                    };

                    moveMarker(); // Start moving the marker
                }).addTo(map);
            }
        });
    }
}



function displayRoute(start, end, callback) {
    var tempRoutingControl = L.Routing.control({
        waypoints: [
            L.latLng(start[0], start[1]), // Start at the current position
            L.latLng(end[0], end[1]) // End at the clicked position
        ]
    }).on('routesfound', function(e) {
        var routes = e.routes;
        console.log(routes);

        // Display the route
        var route = routes[0];
        polyline = L.polyline(route.coordinates.map(function(coord) {
            return [coord.lat, coord.lng];
        })).addTo(map);

        // Remove the temporary routing control
        map.removeControl(tempRoutingControl);  

        // Call the callback function
        callback();
    }).addTo(map);
}
function startRouting(start, end) {
    if (routingControl) {
        map.removeControl(routingControl); // Remove any existing routing control
    }

    if (polyline) {
        map.removeLayer(polyline); // Remove any existing polyline
    }

    const isInsidePark = parkPolygon.getBounds().contains(L.latLng(start)) && parkPolygon.getBounds().contains(L.latLng(end));

    if (isInsidePark) {
        // Create a dashed line between points if both are inside the park
        polyline = L.polyline([start, end], { 
            color: 'blue', 
            weight: 3, 
            dashArray: '10, 10' // Creates a dashed line
        }).addTo(map);
        
        routingMarker.setLatLng(start); // Set the initial position of the marker
        let step = 0;

        // Simulate movement along the line
        const interval = setInterval(() => {
            if (step >= 1) {
                clearInterval(interval); // Stop animation when we reach the end
            } else {
                const currentLat = start[0] + step * (end[0] - start[0]);
                const currentLng = start[1] + step * (end[1] - start[1]);
                routingMarker.setLatLng([currentLat, currentLng]); // Move the marker
                step += 0.01; // Adjust speed of movement
            }
        }, 100); // Adjust speed as necessary
    } else {
        // Call displayRoute to handle routing outside the park
        displayRoute(start, end, function() {
            // Ask for confirmation to start live routing after the route is displayed
            if (confirm("Start live routing?")) {
                routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(start[0], start[1]), // Start at the current position
                        L.latLng(end[0], end[1]) // End at the clicked marker's position
                    ],
                    createMarker: function() { return null; } // Prevent routing markers
                }).on('routesfound', function(e) {
                    var routes = e.routes;
                    console.log(routes);

                    // Animate marker along the route
                    var routeCoordinates = routes[0].coordinates.map(coord => [coord.lat, coord.lng]);
                    let index = 0; // Initialize index to track the current position

                    const moveMarker = () => {
                        if (index < routeCoordinates.length) {
                            routingMarker.setLatLng(routeCoordinates[index]); // Move the marker
                            index++;
                            setTimeout(moveMarker, 100); // Adjust speed as necessary
                        }
                    };

                    moveMarker(); // Start moving the marker
                }).addTo(map);
            }
        });
    }
}

function displayRoute(start, end, callback) {
    var tempRoutingControl = L.Routing.control({
        waypoints: [
            L.latLng(start[0], start[1]), // Start at the current position
            L.latLng(end[0], end[1]) // End at the clicked position
        ]
    }).on('routesfound', function(e) {
        var routes = e.routes;
        console.log(routes);

        // Display the route
        var route = routes[0];
        polyline = L.polyline(route.coordinates.map(function(coord) {
            return [coord.lat, coord.lng];
        })).addTo(map);

        // Remove the temporary routing control
        map.removeControl(tempRoutingControl);  

        // Call the callback function
        callback();
    }).addTo(map);
}


    // JavaScript function to handle redirection to booking.php
    function redirectToBooking() {
            window.location.href = 'booking_home.php'; // Adjust to your actual URL
        }       


    function searchMarkers() {
    const searchQuery = document.getElementById('markerSearch').value.toLowerCase();
    const searchResultsList = document.getElementById('search-results-list');
    searchResultsList.innerHTML = ''; // Clear previous results

    let resultsFound = false; // Track if any search results are found

    waypoints.forEach(waypoint => {
        if (waypoint.name.toLowerCase().includes(searchQuery)) {
            resultsFound = true; // Mark that a search result was found

            const listItem = document.createElement('li');
            listItem.textContent = waypoint.name;

            // Add click event to show the waypoint on the map and display the popup
            listItem.addEventListener('click', function() {
                map.setView([waypoint.lat, waypoint.lon], 16); // Zoom to marker
                waypoint.markerInstance.openPopup(); // Open the popup of the marker
            });

            searchResultsList.appendChild(listItem);
        }
    });

    // Display or hide the search results based on whether results were found
    if (resultsFound) {
        searchResultsList.style.display = 'block'; // Show the list if results are found
    } else {
        searchResultsList.style.display = 'none'; // Hide the list if no results are found
    }
}




    </script>
</body>
</html>


<?php
// PHP function to get markers from the database
function getMarkersFromDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "napwc_db";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM map";
    $result = $conn->query($sql);
    $markers = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $markers[] = $row;
        }
    }
    $conn->close();
    return $markers;
}
?>
