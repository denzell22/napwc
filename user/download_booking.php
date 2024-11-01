<?php
// download_booking.php

// Include TCPDF library
require_once('../TCPDF-main/tcpdf.php');

// Include necessary configurations and database connection
include '../components/config.php';

// Check if ID is provided through GET request
if (isset($_GET['ID'])) {
    $bookingID = $_GET['ID'];

    // Fetch booking information from the database
    $stmt = $conn->prepare("SELECT * FROM booking_records WHERE ID = ?");
    $stmt->bind_param('s', $bookingID);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $bookingData = $result->fetch_assoc();

        // Adjust the dimensions for a standard ticket size (2 inches by 5 inches)
        $ticketWidth = 50.8; // Width in mm (2 inches)
        $ticketHeight = 127; // Height in mm (5 inches)

        // Create a PDF file with booking information
        $pdf = new TCPDF('P', 'mm', array($ticketWidth, $ticketHeight), true, 'UTF-8', false);

        // Set page margins (left, top, right)
        $pdf->SetMargins(3, 2, 2); // Reduce margins
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage(); // Using the ticket size

        // Base path for the images
        $basePath = '../images/';

        // Background images for header and footer
        $headerBg = $basePath . 'napwc_logo.jpg'; 

        // Set opacity for the header image
        $pdf->SetAlpha(0.2);

        // Add header background image
        if (file_exists($headerBg)) {
            $pdf->Image($headerBg, 3.8, 5, 44, 20, '', '', 'center', false, 300, '', false, false, 0, false, false, false);
        }

        // Header
        $pdf->SetAlpha(1);
        $logoPath = $basePath . 'napwc_logo.jpg';
        // Set image scale factor
        if (file_exists($logoPath)) {
            // Set font for header
            $pdf->SetFont('helvetica', 'B', 5); 
            $header = "
                <div style='padding: 2px; text-align: center;'>
                    <img src='{$logoPath}' alt='napwc logo' width='80'>
                    <h2 style='font-size: 6pt;'>Ninoy Aquino Parks and Wildlife Center</h2>
                    <p style='font-size: 5pt;'>Elliptical Road, Bagong Pag-asa, Quezon City, Metro Manila</p>
                </div>
                <hr style='margin: 1px 0; border: 1px solid #000;'> 
            ";
            $pdf->writeHTML($header, true, false, true, false, '');
        } else {
            echo "Logo image not found.";
        }

        // Set transparency for the entire page
        $pdf->SetAlpha(0.2);

        // Big logo at the center
        $bigLogoPath = $basePath . 'denr.jpg'; 

        // Set image scale factor
        $bigLogoWidth = 45;  
        $bigLogoHeight = 45; 
        $pdf->Image($bigLogoPath, $x = ($pdf->getPageWidth() - $bigLogoWidth) / 2, $y = ($pdf->getPageHeight() - $bigLogoHeight) / 2, $bigLogoWidth, $bigLogoHeight);

        // Reset alpha to fully opaque
        $pdf->SetAlpha(1);

        // Set font for booking information
        $pdf->SetFont('helvetica', 'B');
        $content = "
            <div style='width: 46mm; margin: 0; padding: 2mm 0;'> 
                <fieldset style='padding: 5px; border: 1px solid #000; margin-bottom: 5mm;'> <!-- Added margin-bottom -->
                <legend><h3 style='font-size: 7pt;'>Booking Information</h3></legend>
                    <p style='font-size: 5pt;'><strong>Booking ID:</strong> {$bookingData['ID']}</p>
                    <p style='font-size: 5pt;'><strong>Name:</strong> {$bookingData['NAME']} {$bookingData['lastname']}</p>
                    <p style='font-size: 5pt;'><strong>Date:</strong> {$bookingData['DATE']}</p>
                    <p style='font-size: 5pt;'><strong>Section:</strong> {$bookingData['SECTION']}</p>
                    <p style='font-size: 5pt;'><strong>Price:</strong> {$bookingData['PRICE']} Pesos Only</p>
                </fieldset>
                <hr style='margin: 0; border: 1px solid #000;'> 
            </div>
        ";
        $pdf->writeHTML($content, true, false, true, false, '');

        // Set font for important notes
        $pdf->SetFont('helvetica', ''); 
        $notes = "
            <div style='width: 46mm; margin: 0; padding: 2mm 0;'> 
            <h3 style='font-size: 6pt; color: red;'>Important Notes</h3>
            <p style='font-size: 4pt;'>This form should be used as your official receipt for park accommodation.</p>
            <p style='font-size: 4pt;'>Show this form to the park's front desk for confirmation, verification, and settlement of payment.</p>
            <p style='font-size: 4pt;'>For inquiries: (+63)910-304-0899 | 924-60-31</strong></p>
            <hr style='margin: 0; border: 1px solid #000;'>
            </div>
        ";
        $pdf->writeHTML($notes, true, false, true, false, '');

        // Set font for poster and contact information
        $pdf->SetFont('helvetica', '', 5); // Regular font, size 5
        $poster = "
            <div style='width: 46mm; margin: 0; padding: 2mm 0;'>  
                <p style='font-size: 4pt;'>Department of Environment & Natural Resources</p>
                <p style='font-size: 3pt;'>BIODIVERSITY MANAGEMENT BUREAU</p>
                <img src='{$logoPath}' alt='napwc logo' width='80'>
            </div>
        ";
        $pdf->writeHTML($poster, true, false, true, false, '');

        // Output the PDF file
        $pdf->Output("booking$bookingID.pdf", 'D');

        // Close the database connection
        $conn->close();
        exit();
    }
}

// If ID is not provided or booking information retrieval fails
echo "Failed to download booking information.";
?>
