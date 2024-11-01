<?php 

include '../components/config.php';

$registrationMessage = ""; // Initialize the registration message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission (registration)
    $username = $_POST['username'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $organization = $_POST['organization'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    // Check if the username is already taken
    $checkUsernameQuery = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($checkUsernameQuery);

    if ($result->num_rows > 0) {
        $registrationMessage = "USERNAME IS ALREADY TAKEN PLEASE USE A UNIQUE ONE!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Insert into the database
        $insertQuery = "INSERT INTO user (username, password, first_name, middle_name, last_name, organization, address, contact, email, status)
            VALUES ('$username', '$hashedPassword', '$first_name', '$middle_name', '$last_name', '$organization', '$address', '$contact', '$email', 'Active')";

        try {
            $conn->query($insertQuery);
            $registrationMessage = "Registration Successful!";
        } catch (Exception $e) {
            $registrationMessage = "Error: " . $e->getMessage();
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="register.css">
    <script>
    // JavaScript code for message handling
    document.addEventListener('DOMContentLoaded', function () {
        var messageElement = document.getElementById('registrationMessage');

        // Check if the message element exists and contains a success message
        if (messageElement && messageElement.innerText.includes('successful')) {
            // Hide the message after 3 seconds and reset the form
            setTimeout(function () {
                messageElement.style.display = 'none';
                document.querySelector('form').reset();
            }, 3000);
        }
    });
    </script>
</head>

<body>
    <h2>User Registration</h2>
    <div id="registrationMessage" style="color: white; text-align: center; margin-top: 10px; margin-bottom: 10px; background-color: #023020; padding: 10px; border-radius: 5px;">
        <?php echo $registrationMessage; ?>
    </div>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required><br>

        <label for="middle_name">Middle Name:</label>
        <input type="text" name="middle_name"><br>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required><br>

        <label for="organization">Organization:</label>
        <select name="organization" required>
            <option value="Government">Government</option>
            <option value="Non-Government">Non-Government</option>
        </select><br>

        <label for="address">Address:</label>
        <input type="text" name="address" required><br>

        <label for="contact">Contact:</label>
        <input type="text" name="contact" pattern="[0-9]{11}" title="Please enter a valid 11-digit contact number" maxlength="11" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <button type="submit">Register</button><br>
        <a href="../user/login.php"> Already have an account? </a>
    </form>
</body>

</html>