<?php

session_start();
session_unset();
session_destroy();

// Redirect to the login page or home page (index.php) within the correct folder path
header("Location: ../Productpage.php"); // Updated to include the folder path

exit();
