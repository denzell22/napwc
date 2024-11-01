<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../components/config.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user is an admin
    $adminSql = "SELECT * FROM admin WHERE BINARY admin_name = ?";
    $adminStmt = $conn->prepare($adminSql);
    $adminStmt->bind_param("s", $username);
    $adminStmt->execute();
    $adminResult = $adminStmt->get_result();

    if ($adminResult->num_rows == 1) {
        $adminRow = $adminResult->fetch_assoc();
        $hashedPassword = $adminRow['admin_password'];

        // Verify the entered password against the MD5 hashed password
        if (password_verify($password, $hashedPassword)) {
            // Admin login successful, set session variables
            $_SESSION['user_id'] = $adminRow['id'];
            $_SESSION['username'] = $adminRow['admin_name'];
            $_SESSION['user_type'] = 'admin';

            // Redirect to admin home page
            header("Location: ../admin/admin_home.php");
            exit();
        }
    }

    // User login
    $userSql = "SELECT id, username, password FROM user WHERE BINARY username = ?";
    $userStmt = $conn->prepare($userSql);
    $userStmt->bind_param("s", $username);
    $userStmt->execute();
    $userResult = $userStmt->get_result();

    if ($userResult->num_rows == 1) {
        $row = $userResult->fetch_assoc();
        $hashedPassword = $row['password'];

        // Verify the entered password against the hashed password
        if (password_verify($password, $hashedPassword)) {
            // User login successful, set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_type'] = 'user';

            // Update user status to "active"
            $updateStatusSql = "UPDATE user SET status = 'Active' WHERE id = ?";
            $updateStatusStmt = $conn->prepare($updateStatusSql);
            $updateStatusStmt->bind_param("i", $row['id']);
            $updateStatusStmt->execute();
            $updateStatusStmt->close();

            // Redirect to user's home page or any other page after successful login
            header("Location: homepage_logged.php");
            exit();
        }
    }

    // Login failed
    $login_error = "Invalid username or password. Please try again.";

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="login.css">
 
</head>

<body>

<div class="page-container">
    <div class="header">
        <?php include '../components/user_header.php'; ?>
    </div>

    <div class="login-content">
        <h2 class="text-center">Login</h2>
        <?php
        if (isset($login_error)) {
            echo '<p class="error-message">' . $login_error . '</p>';
        }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <!-- Register Link -->
        <p class="text-center mt-3">Doesn't have an account yet? <a href="../user/register.php">Register</a></p>
    </div>

    <div class="footer">
        <?php include '../components/phone_footer.php'; ?>
    </div>
</div>

</body>
</html>