<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .navbar-container {
            background-color: #333;
            color: white;
            padding: 20px;
            height: 200px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative; /* Position relative for dropdown */
        }

        .logo img {
            height: 100px; /* Further reduced logo height */
            border-radius: 20px;
        }

        .navbar {
            display: flex;
            justify-content: flex-start;
        }

        .navbar button {
            width: 100px;
            padding: 10px;
            margin-right: 15px;
            border: 1px solid #4C6444;
            border-radius: 10px;
            background-color: #4C6444;
            cursor: pointer;
        }

        .navbar button:hover {
            background-color: #B68D40;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            background-color: #4C6444;
            color: white;
            padding: 10px;
            border: 1px solid #4C6444;
            border-radius: 10px;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #4C6444;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            font-size: 15px;
            color: white;
            padding: 5px;
            text-decoration: none;
            display: block;
            border-radius: 10px;
        }

        .dropdown-content a:hover {
            background-color: #B68D40;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>

<div class="navbar-container">
    <div class="logo-container">
        <a href="../admin/admin_home.php" class="logo"><img src='../images/napwc_logo.png'></a>
    </div>

    <nav class="navbar">
        <button onclick="window.location.href = 'admin_home.php';">Home</button>
        <div class="dropdown">
            <button class="dropbtn">Users</button>
            <div class="dropdown-content">
                <a href="../admin/admin_users.php">User Information</a>
                <a href="../admin/admin.php">Appointment Requests</a>
                <a href="../admin/admin_inbox.php">User Messages</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Events</button>
            <div class="dropdown-content">
                <a href="../admin/admin_addevents.php">Add Event</a>
                <a href="../admin/admin_archiveevents.php">Archive Event</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Facilities</button>
            <div class="dropdown-content">
                <a href="../admin/addfaci.php">Add New</a>
                <a href="../admin/updatefaci.php">Update Facility Information</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Records</button>
            <div class="dropdown-content">
                <a href="../admin/treeuniverse_index.php">Tree Universe</a>
                <a href="../admin/map_index.php">Map</a>
            </div>
        </div>
        <button onclick="window.location.href = '../user/home.php';">Log Out</button>
    </nav>
</div>

</body>
</html>
