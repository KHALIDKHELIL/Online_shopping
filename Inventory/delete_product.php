<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $product = find_by_id('product', (int)$_GET['Id']);
  if(!$product){
    $session->msg("d","Missing Product id.");
    redirect('product.php');
  }
?>
<?php
  $delete_id = delete_by_id('product',(int)$product['Id']);
  if($delete_id){
      $session->msg("s","Products deleted.");
      redirect('product.php');
  } else {
      $session->msg("d","Products deletion failed.");
      redirect('product.php');
  }
?>
