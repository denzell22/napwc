<?php
// download_booking.php

// Include TCPDF library
require_once('../TCPDF-main/tcpdf.php');

// Include necessary configurations and database connection
include '../components/config.php';

// Fetch the selected time frame from the query parameters
$timeFrame = isset($_GET['time_frame']) ? $_GET['time_frame'] : 'all';

// Adjust the SQL query based on the selected time frame
if ($timeFrame === 'daily') {
    // Filter by daily records for the current date
    $sql = "SELECT * FROM booking_records WHERE DATE = CURDATE() ORDER BY DATE ASC";
} elseif ($timeFrame === 'weekly') {
    // Filter by weekly records for the current week
    $sql = "SELECT * FROM booking_records WHERE DATE BETWEEN CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY AND CURDATE() ORDER BY DATE ASC";
} elseif ($timeFrame === 'monthly') {
    // Filter by monthly records for the current month
    $sql = "SELECT * FROM booking_records WHERE MONTH(DATE) = MONTH(CURDATE()) AND YEAR(DATE) = YEAR(CURDATE()) ORDER BY DATE ASC";
} else {
    // Fetch all records, arranged by date in ascending order
    $sql = "SELECT * FROM booking_records ORDER BY DATE ASC";
}

$result = $conn->query($sql);

// Check for errors in the query
if (!$result) {
    echo "Failed to download booking information.";
    exit();
}

// Create a PDF file with the fetched booking information
$pdf = new TCPDF();
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Base path for the image
$basePath = '../images/';

$logoPath = $basePath . 'napwc_logo.jpg';
$pdf->Image($logoPath, $x = 140, $y = 20, $w = 50);
$header = "
    <img src='{$logoPath}' width='100'>
    <h2>Ninoy Aquino Parks and Wildlife Center</h2>
    <p>Elliptical Road, Bagong Pag-asa, Quezon City, Metro Manila</p>
";
$pdf->writeHTML($header, true, false, true, false, '');

// Set transparency for the entire page
$pdf->SetAlpha(0.2);

// Big logo at the center
$bigLogoPath = $basePath . 'denr.jpg'; // Replace with the actual filename and path

// Set image scale factor
$bigLogoWidth = 150;  // Adjust the width as needed
$bigLogoHeight = 150; // Adjust the height as needed
$pdf->Image($bigLogoPath, $x = ($pdf->getPageWidth() - $bigLogoWidth) / 2, $y = ($pdf->getPageHeight() - $bigLogoHeight) / 2, $bigLogoWidth, $bigLogoHeight);

// Reset alpha to fully opaque
$pdf->SetAlpha(1);

// Booking Information Table
$content = "<br><h1>All Booking Records</h1>";
$content .= "<table border='1'>";
$content .= "<thead><tr><th>Booking ID</th><th>Name</th><th>Last Name</th><th>Date</th><th>Section</th><th>Price</th></tr></thead>";
$content .= "<tbody>";

while ($bookingData = $result->fetch_assoc()) {
    $content .= "
        <tr>
            <td>{$bookingData['ID']}</td>
            <td>{$bookingData['NAME']}</td>
            <td>{$bookingData['lastname']}</td>
            <td>{$bookingData['DATE']}</td>
            <td>{$bookingData['SECTION']}</td>
            <td>{$bookingData['PRICE']} Pesos</td>
        </tr>
    ";
}

$content .= "</tbody></table>";
$pdf->writeHTML($content, true, false, true, false, '');

// Important Notes (Smaller font)
$notes = "
<hr>
    <div style='margin-top: 20px; font-size: 7px;'>
        <h3>Important Notes</h3>
        <p>This form will be the guide for the park's front desk in confirming the guest's booking appointments.</p>
        <p>The records on this form should match the details shown on the guest's booking form.</p>
    </div>
<hr>
";
$pdf->writeHTML($notes, true, false, true, false, '');

$pdf->Output("Admin Booking Records.pdf", 'D');

// Close the database connection
$conn->close();
exit();
?>