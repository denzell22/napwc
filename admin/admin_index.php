<?php
session_start();
    // Include the database connection file
    include '../components/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_index.css">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <title>Login</title>

</head>
<body>

<div class="page-container">
    <div class="header">
        <?php include '../components/admin_header.php'; ?>
    </div>

    <div class="workspace-container">
        <div class="sidebar">
            <div class="login-content">
                <h2>Admin Login</h2>
                <?php
                if (isset($login_error)) {
                    echo '<p class="error-message">' . $login_error . '</p>';
                }
                ?>
                <form action="admin_login.php" method="post">
                    <label for="username">Admin Username</label><br>
                    <input type="text" id="admin_username" name="admin_username" required><br>
                    <label for="password">Password</label><br>
                    <input type="password" id="admin_password" name="admin_password" required><br>
                    <input type="submit" value="Login">
                </form>
            </div>
        </div>
    </div>

    <div class="footer">
        <?php include '../components/footer.php'; ?>
    </div>
</div>

</body>
</html>
