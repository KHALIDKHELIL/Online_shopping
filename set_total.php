<?php
session_start();

if (isset($_POST['total'])) {
  $_SESSION['total'] = $_POST['total'];
}
?>
