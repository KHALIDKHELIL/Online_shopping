<?php

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";
include 'config.php';

// Ensure the payment success flag is set
if (!isset($_SESSION['payment_success']) || $_SESSION['payment_success'] !== true) {
    die("Unauthorized access or payment not completed.");
}

// Get the last order ID from the session
$order_id = $_SESSION['Last_orderid'];

// Retrieve payment and user details from the database
$sql = "SELECT p.amount, p.Currency, c.userFname, c.userLname, c.userEmail 
        FROM payments p 
        JOIN customer c ON p.PUserid = c.userID 
        WHERE p.POrderId = '$order_id'";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Error: Could not retrieve payment details. " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

// Extract details from the query
$user_firstname = $row['userFname'];
$user_lastname = $row['userLname'];
$user_email = $row['userEmail'];
$amount = $row['amount'];
$currency = $row['Currency'];

// Fetch additional order details
$sql2 = "SELECT OrderId, userid, city, Kebele, country, states, Payment_Method, Order_Status, Phone_Number FROM customorder where OrderId='{$order_id}'";
$result2 = mysqli_query($conn, $sql2);

if (mysqli_num_rows($result2) > 0) {
    $order_details = mysqli_fetch_assoc($result2);
    $orderId = $order_details['OrderId'];
    $userId = $order_details['userid'];
    $city = $order_details['city'];
    $kebele = $order_details['Kebele'];
    $country = $order_details['country'];
    $state = $order_details['states'];
    $paymentMethod = $order_details['Payment_Method'];
    $orderStatus = $order_details['Order_Status'];
    $phoneNumber = $order_details['Phone_Number'];


    // Create the personalized message
    $subject = "Payment Confirmation - Order #$order_id";
    $body = "
<div style='background-color: #f9f9f9; padding: 20px; border-radius: 8px;'>
<h3 style='color: #2c3e50;'>Thank You for Your Purchase!</h3>
<div style='margin-bottom: 15px;'>
   <span style='font-weight: bold;'>Items:</span> " . "Total: <span style='color:#e74c3c;'>$" . $amount . "</span>
</div>
<div style='background-color: #ecf0f1; padding: 10px; border-radius: 5px;'>
   <p style='margin: 5px 0;'>Your Name: <span style='color: #34495e; font-weight: bold;'>" . $user_firstname . " " . $user_lastname . "</span></p>
   <p style='margin: 5px 0;'>Your Email: <span style='color: #34495e; font-weight: bold;'>" . $user_email . "</span></p>
   <p style='margin: 5px 0;'>Your Address: <span style='color: #34495e; font-weight: bold;'>"  . $city . ", " . $state . ", " . $country . "</span></p>
   <p style='margin: 5px 0;'>Your Payment Mode: <span style='color: #34495e; font-weight: bold;'>" . $paymentMethod . "</span></p>
   <p style='color: #3498db; font-weight: bold;'>Expect your delivery within a week of purchase. We will call you once it is delivered.</p>
   <p>If you need any assistance, feel free to contact our support team at <a href='mailto:support@brand.com' style='color:#3498db; text-decoration: none;'>support@brand.com</a></p>
</div>
<a href='http://localhost:7880/Final_Project/productpage.php' style='background-color: #2ecc71; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px;'>Continue Shopping</a>
</div>
";
} else {
    die("Error: Could not retrieve order details.");
}
// Send the email
$mail = new PHPMailer(true);

try {
    // Enable debugging
    $mail->SMTPDebug = 0; // Detailed debugging


    // SMTP server configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'khalidkhelil19@gmail.com'; // Replace with your Gmail address
    $mail->Password = 'umgunlsmnojktcck'; // Replace with your Gmail app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email configuration
    $mail->setFrom('khalidkhelil19@gmail.com', 'Brand'); // Replace with your email and business name
    $mail->addAddress($user_email); // Add recipient email
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;

    // Send email
    $mail->send();
} catch (Exception $e) {
    echo "Failed to send email. Error: {$mail->ErrorInfo}";
}
