<?php
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(3);

// Validate the presence of the `id` parameter in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $session->msg("d", "Missing or invalid payment ID.");
    redirect('sales.php');
}

// Retrieve the payment record by `PayId`
$d_payment = find_by_id('payments', (int)$_GET['PayId']);
if (!$d_payment) {
    die("Payment record not found. Debug info: ID = {$_GET['id']}"); // Debugging output
}

// Attempt to delete the payment record
$delete_id = delete_by_id('payments', (int)$d_payment['PayId']);
if ($delete_id) {
    $session->msg("s", "Payment record deleted successfully.");
    redirect('sales.php');
} else {
    $session->msg("d", "Failed to delete the payment record.");
    redirect('sales.php');
}
?>
