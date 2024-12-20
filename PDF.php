<?php

// ob_start();
require __DIR__ . "/vendor/autoload.php";
session_start();
@include 'db_connc.php';

use Dompdf\Dompdf;

// $dompdf = new Dompdf();
// // $dompdf = new Dompdf;
// $dompdf->loadHtml("<html><body><h1>Here is Your reset!!!!!!!!</h1></body></html>");

// $dompdf->render();
// // Save the pdf file on the server
// file_put_contents('Myreset.pdf', $output);


// // Send the PDF to the browser
// $dompdf->stream('Myreset.pdf');

// ob_end_clean(); // Clean (erase) the output buffer without sending it

// Redirect to cart.php after displaying the PDF
// echo '<meta http-equiv="refresh" content="5;url=cart.php">';
// exit(); // Make sure to exit after redirection

function generateAndDownloadPDF()
{
    $dompdf = new Dompdf();
    $dompdf->loadHtml("<html><body><h1>Here is Your reset!!!!!!!!</h1></body></html>");

    $dompdf->render();
    // Save the pdf file on the server
    $output = $dompdf->output();
    file_put_contents('Myreset.pdf', $output);

    $dompdf->stream('Myreset.pdf');
    exit(); // Ensure to exit after downloading the PDF
}

if (isset($_POST['order_btn'])) {
    generateAndDownloadPDF();
    // $dompdf = new Dompdf();
    // // $dompdf = new Dompdf;
    // $dompdf->loadHtml("<html><body><h1>Here is Your reset!!!!!!!!</h1></body></html>");

    // $dompdf->render();

    // $output = $dompdf->output();
    // // Save the pdf file on the server
    // file_put_contents('Myreset.pdf', $output);
    // $dompdf->stream('Myreset.pdf');

    // Capture form data
    $amount = $_POST['amount'];
    $currency = $_POST['currency'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $status = $_POST['Status'];

    // Perform the SQL INSERT operation
    // $insert_query = "INSERT INTO payments (first_name, last_name, email, amount, Currency,status) 
    // VALUES ('$first_name', '$last_name', '$email', '$amount', '$currency', '$status')";
    mysqli_query($conn, "INSERT INTO payments(first_name, last_name, email, amount, Currency,status) 
    VALUES ('$first_name', '$last_name', '$email', '$amount', '$currency', '$status')");
    echo "Inserted Succesfullyy";

    // Redirect back to the form page or any other page after insertion
    header("Location: cart.php"); // Redirect to your desired page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5; URL=cart.php">
    <title>Redirecting...</title>
</head>

<body>
    <h1>You Payed Succeully!!!!!!!!!!!</h1>

</body>

</html>