<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trees at Ninoy Aquino Parks and Wildlife Center</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="treeuniv.css">
</head>


<body>

<!-- Scanner Icon at the top right corner -->
<a href="scanner.php" class="scanner-icon">
    <div class="scanner-content">
        <i class="fas fa-qrcode"></i> <!-- Font Awesome QR code icon -->
        <span class="scanner-label">Scan Tree</span> <!-- Distinctive name -->
    </div>
</a>

<div class="header">
    <?php include '../components/user_header.php';?>
</div>

<h5>Trees at Ninoy Aquino Parks and Wildlife Center</h5>

<div class="tree-container">
<?php
// Include config file
require_once "../components/config.php";

$sql = "SELECT id, tree_picture, tree_name, scientific_name, description, habitat, distribution, conservation_status FROM trees";
$result = $conn->query($sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="tree-card">';
        echo '<div class="tree-image-container">';
        echo '<img class="tree-image" src="' . htmlspecialchars($row['tree_picture']) . '" alt="' . htmlspecialchars($row['tree_name']) . ' - Image">';
        echo '</div>';
        echo '<h6>' . htmlspecialchars($row['tree_name']) . '</h6>';
        echo '<p>Scientific Name: ' . htmlspecialchars($row['scientific_name']) . '</p>';
        echo '<p>Description: ' . htmlspecialchars($row['description']) . '</p>';
        echo '<p>Habitat: ' . htmlspecialchars($row['habitat']) . '</p>';
        echo '<p>Distribution: ' . htmlspecialchars($row['distribution']) . '</p>';
        echo '<p>Conservation Status: ' . htmlspecialchars($row['conservation_status']) . '</p>';
        // echo '<button class="view-button" onclick="redirectToTunnel(' . $row['id'] . ')">View</button>';
        echo '</div>';
    }
} else {
    echo "<p>No records found</p>";
}

$conn->close();
?>
</div>

<div class="footer">
    <?php include '../components/phone_footer.php';?>
</div>

<script>
    function redirectToTunnel(treeId) {
        // Implement redirection logic to view specific tree details
        window.location.href = 'tree_detail.php?id=' + treeId;
    }
</script>

</body>
</html>
