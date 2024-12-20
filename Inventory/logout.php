<?php
  require_once('includes/load.php');
  if(!$session->logout()) {redirect("../login/index-one.php");}
?>
