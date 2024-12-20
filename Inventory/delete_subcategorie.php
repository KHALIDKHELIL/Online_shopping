<?php
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(1);

// Fetch the subcategory by ID
$subcategory = find_by_id('subcatgory', (int)$_GET['Id']);
if (!$subcategory) {
    $session->msg("d", "Missing Subcategory id.");
    redirect('categorie.php');
}

// Attempt to delete the subcategory
$delete_id = delete_by_id('subcatgory', (int)$subcategory['Id']);
if ($delete_id) {
    $session->msg("s", "Subcategory deleted successfully.");
    redirect('categorie.php');
} else {
    $session->msg("d", "Subcategory deletion failed.");
    redirect('categorie.php');
}
?>
