<?php
Session_Start();
if(!$_SESSION['userid']){
    header('location: ./login/index-one.php');
}
?>