<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "napwc";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

    // Your registration logic goes here
    // For demonstration purposes, you can print the submitted data
    echo "Username: $username<br>";
    echo "Password: $password<br>";
    echo "First Name: $first_name<br>";
    echo "Middle Name: $middle_name<br>";
    echo "Last Name: $last_name<br>";
    echo "Organization: $organization<br>";
    echo "Address: $address<br>";
    echo "Contact: $contact<br>";

    // Insert into the database
    $sql = "INSERT INTO admin (admin_username, admin_password, admin_first_name, admin_middle_name, admin_last_name, admin_organization, admin_address, admin_contact, admin_status)
            VALUES ('$username', '$password', '$first_name', '$middle_name', '$last_name', '$organization', '$address', '$contact', 'Active')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
    <title>Admin Registration</title>
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
</head>

<body>
    <h2>User Registration</h2>

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
        <input type="text" name="contact" required><br>

        <button type="submit">Register</button>
    </form>
</body>

</html>