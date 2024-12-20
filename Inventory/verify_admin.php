<?php
require_once('includes/load.php');

// Ensure database connection is established
$conn = new mysqli('localhost', 'root', '', 'online_shopping');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$page_title = 'Verify Admin';
include_once('layouts/header.php');

if (isset($_GET['verification'])) {
    $verification_code = $_GET['verification'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE verification_token=? AND status=0");
    $stmt->bind_param("s", $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE admin SET status=1, verification_token=NULL WHERE verification_token=?");
        $stmt->bind_param("s", $verification_code);
        if ($stmt->execute()) {
            $msg = "Your account has been verified successfully. Redirecting to the login page...";
            $redirect = true;
        } else {
            $msg = "Error: Could not verify your account. Please try again.";
            $redirect = true;
        }
    } else {
        $msg = "Invalid or expired verification link.";
        $redirect = true;
    }
} else {
    $msg = "No verification token provided.";
    $redirect = true;
}

echo "<div class='alert alert-info'>{$msg}</div>";

// Add redirection meta tag for successful verification
if (isset($redirect) && $redirect) {
    echo "<meta http-equiv='refresh' content='5;url=http://localhost:7880/Final_Project/login/index-one.php'>";
}
include_once('layouts/footer.php');
?>
