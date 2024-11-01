<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="../images/napwc_logo.jpg">
    <link rel="stylesheet" href="scanner_logged.css">
    <title>QR SCANNER</title>
</head>

<body>
    <?php include '../components/user_header2.php';?>

    <div id="scanner-container" class="container">
        <div id="reader"></div>
        <div id="scan-result-container">
            <h4 class="mb-3">SCAN RESULT</h4>
        </div>
    </div>

    <footer class="footer">
        <?php include '../components/phone_footer.php'; ?>
    </footer>

    <!-- Bootstrap JS and Popper.js (Optional) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script src="html5-qrcode.min.js"></script>

    <script type="text/javascript">
        function onScanSuccess(qrCodeMessage) {
            // Open the link automatically
            window.location.href = qrCodeMessage;
        }

        function onScanError(errorMessage) {
            // Handle scan error
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: 250
            });
        html5QrcodeScanner.render(onScanSuccess, onScanError);

    </script>

</body>

</html>