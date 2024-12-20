<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2); // Adjust user level permissions as needed
?>

<?php
  // Check if the purchase ID is provided
  $purchase = find_by_id('purchase', (int)$_GET['Id']);
  if (!$purchase) {
    $session->msg("d", "Missing Purchase ID.");
    redirect('purchase.php'); // Redirect to the purchase management page
  }
?>

<?php
  // Attempt to delete the purchase
  $delete_id = delete_by_id('purchase', (int)$purchase['Id']);
  if ($delete_id) {
    $session->msg("s", "Purchase deleted successfully.");
    redirect('purchase.php');
  } else {
    $session->msg("d", "Purchase deletion failed.");
    redirect('purchase.php');
  }
?>
