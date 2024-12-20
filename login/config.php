<?php
$server_name = "localhost";
$username = "root";
$password = "";
$db_name = "online_shopping";  // Corrected the database name 

$conn = mysqli_connect($server_name, $username, $password, $db_name);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());  // Added more detailed error message
}
?>
