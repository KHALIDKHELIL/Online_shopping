<?php
require_once('includes/load.php');
// Checkin What level user has permission to view this page
 page_require_level(1);
?>
<?php
$categorie = find_by_id('suppliers', (int)$_GET['Id']);
if (!$categorie) {
  $session->msg("d", "Missing Categorie id.");
  redirect('Supplier.php');
}
?>
<?php
$delete_id = delete_by_id('suppliers', (int)$categorie['Id']);
if ($delete_id) {
  $session->msg("s", "Categorie deleted.");
  redirect('Supplier.php');
} else {
  $session->msg("d", "Categorie deletion failed.");
  redirect('Supplier.php');
}
?>
