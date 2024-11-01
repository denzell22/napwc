<?php
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    include ('../components/config.php');

    // Get admin input
    $user_input = $_POST['admin_username'];
    $pass_input = $_POST['admin_password'];

    // SQL query to check admin credentials
    $sql = "SELECT * FROM admin WHERE admin_name = ? AND admin_password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_input, $pass_input);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Login successful, set session variables
        $row = $result->fetch_assoc();
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_username'] = $row['admin_name'];

        // Redirect to user's home page or any other page after successful login
        header("Location: admin_home.php");
        exit(); 
    } else {
        // Login failed
        $login_error = "Invalid username or password. Please try again.";
    }

    // Close database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_login.css">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <title>Login</title>

</head>
<body>

<div class="page-container">
    <div class="header">
        <?php include '../components/admin_header_loggedin.php'; ?>
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
                    <input type="text" id="username" name="admin_username" required><br>
                    <label for="password">Password</label><br>
                    <input type="password" id="password" name="admin_password" required><br>
                    <input type="submit" value="Login">
                </form>
            </div>
        </div>

        <div class="main-workspace">
            <div class="hello-admin">
                <h3>Welcome to NAPWC!</h3>
            </div>

            <div class="avp-container">
                <video controls>
                    <source src="example.mp4" type="video/mp4">
                </video>
            </div>
        </div>
    </div>

    <div class="footer">
        <?php include '../components/footer.php'; ?>
    </div>
</div>

</body>
</html>