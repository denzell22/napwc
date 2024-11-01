<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>
   <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>

<div class="page-container">

    <div class="header">
        <?php
        // Include the header.php file
        include '../components/admin_header_loggedin.php';
        ?>
    </div>

    <div class="workspace-container">

    <div class="sidebar">
            <div class="menu-content">
                 <h2>Admin Login</h2>
                <ul class="button-list">
                    <li><button onclick="window.location.href='admin_users.php'">Users Information</button></li>
                    <li><button onclick="window.location.href='admin_users.php'">Appointment</button></li>
                    <li><button onclick="window.location.href='admin_messages.php'">Messages</button></li>
                </ul>   
            </div>   
    </div>

    <div class="main-workspace">
        <div class="hello-admin">
            <h3>Welcome to NAPWC Administrator Module!</h3>
        </div>     

            <!-- Add AVP Container -->
        <div class="avp-container">
            <!-- Add your audio visual videos here -->
            <!-- Example: -->
            <video controls>
                <source src="example.mp4" type="video/mp4">
            </video>
        </div>
    </div>

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