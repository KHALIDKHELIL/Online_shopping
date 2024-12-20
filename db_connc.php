<?php

$sname = "localhost";
$unmae = "root";
$password = "";
$db_name = "online_shopping";

$conn = mysqli_connect($sname, $unmae, $password, $db_name);

if (!$conn) {
	echo "Connection failed!";
}
