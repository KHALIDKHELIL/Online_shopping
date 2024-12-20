<?php
require __DIR__ . "/vendor/autoload.php";
include 'db_connc.php'; // Database connection
// include 'Security.php'; // Optional security validations

use Dompdf\Dompdf;

// Start session
session_start();

// Retrieve and validate callback data
$order_id = $_SESSION['Last_orderid'] ?? null;
$payment_status = $_POST['status'] ?? null;
$amount = $_POST['amount'] ?? null;
$currency = $_POST['currency'] ?? null;
$tx_ref = $_POST['tx_ref'] ?? null;

if (!$order_id || !$payment_status) {
    die("Invalid callback data received!: $order_id");
}

// Update the order status in the database
if ($payment_status === 'success') {
    $sql = "UPDATE payments SET OrderStatus='Well Payed' WHERE POrderId='$order_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Payment Successful!');</script>";
    } else {
        die("Error updating order status: " . mysqli_error($conn));
    }

    // Fetch updated order details for the receipt
    $sqlp = "SELECT * FROM payments WHERE POrderId='$order_id'";
    $result = mysqli_query($conn, $sqlp);
    if (mysqli_num_rows($result) > 0) {
        $payment_details = mysqli_fetch_assoc($result);
    } else {
        die("Payment record not found.");
    }

    // Generate and download the PDF receipt
    function generateAndDownloadPDF($payment_details)
    {
        $dompdf = new Dompdf();
        ob_start();

        // Include HTML template for receipt
        echo "<h1>Payment Receipt</h1>";
        echo "<p>Order ID: {$payment_details['POrderId']}</p>";
        echo "<p>User ID: {$payment_details['PUserid']}</p>";
        echo "<p>Amount Paid: {$payment_details['amount']} {$payment_details['Currency']}</p>";
        echo "<p>Status: {$payment_details['OrderStatus']}</p>";
        echo "<p>Transaction Reference: {$payment_details['tx_ref']}</p>";
        echo "<p>Date: " . date("Y-m-d H:i:s") . "</p>";

        // Get the content as HTML
        $html = ob_get_clean();

        // Load HTML into dompdf
        $dompdf->loadHtml($html);

        // Render as PDF
        $dompdf->render();

        // Stream the PDF to the browser
        $dompdf->stream('Payment_Receipt.pdf');
        exit();
    }

    // Call the function to generate and download the receipt
    generateAndDownloadPDF($payment_details);
} else {
    echo "<script>alert('Payment failed. Please try again.');</script>";
    die("Payment failed or invalid status received. $payment_status");
}

// Close the database connection
mysqli_close($conn);
?>
