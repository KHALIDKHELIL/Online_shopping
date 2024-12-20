<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  // page_require_level(2);
?>
<?php
  $product = find_by_id('stock', (int)$_GET['Id']);
  if(!$product){
    $session->msg("d","Missing stock id.");
    redirect('stock.php');
  }
?>
<?php
  $delete_id = delete_by_id('stock',(int)$product['Id']);
  if($delete_id){
      $session->msg("s","stock deleted.");
      redirect('stock.php');
  } else {
      $session->msg("d","stock deletion failed.");
      redirect('stock.php');
  }
?>
