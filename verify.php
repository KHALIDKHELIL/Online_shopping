<?php

require __DIR__ . "/vendor/autoload.php";
include 'Security.php';

@include 'db_connc.php';

use Dompdf\Dompdf;
$userid = $_SESSION['userid'];

// Ensure the last order ID is set in the session
if (!isset($_SESSION['Last_orderid'])) {
    
    die("Unauthorized access.");
}

$id = $_SESSION['Last_orderid']; // Fetch the last order ID
// Update the payment status

$sql = "UPDATE payments SET OrderStatus='Payed' WHERE POrderId='$id'";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Payment Successful!')</script>";
    $_SESSION['payment_success'] = true; // Set a flag for successful payment
} else {
    die("Error: " . mysqli_error($conn));
}

// Fetch payment details for confirmation
$sqlp = "SELECT * FROM payments WHERE POrderId= '$id'";
$result = mysqli_query($conn, $sqlp);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $POid = $row["POrderId"] . "<br />";
        $PUserid = $row["PUserid"] . "<br />";
        $PMoney = $row["amount"] . "<br />";
        $PType = $row["Currency"] . "<br />";

    
    }
} else {
    // Query execution failed
    echo "Query failed: " . mysqli_error($conn);
}

// Fetch products in the user's cart and update stock
$sql_cart = "SELECT od.Quantity as ordered_quantity,p.Id as product_id,s.itemId as stockitem_id ,s.quantity as stock_quantity FROM customer c
              JOIN customorder co ON c.userid=co.userid
              JOIN orderdetail od ON co.OrderId=od.Orderid 
              JOIN product p ON p.Id=od.Productid JOIN stock s on p.Id=s.itemId  
              WHERE   od.Orderid= '$id' ";

$result_cart = mysqli_query($conn, $sql_cart);

if (mysqli_num_rows($result_cart) > 0) {
    while ($row = mysqli_fetch_assoc($result_cart)) {
        $product_id = $row['product_id'];
        $ordered_quantity = $row['ordered_quantity'];
        $stock_id = $row['stockitem_id'];
        $stock_quantity = $row['stock_quantity'];

        // Check if stock is sufficient
        if ($stock_quantity >= $ordered_quantity) {
            // Subtract ordered quantity from stock
            $new_stock_quantity = $stock_quantity - $ordered_quantity;
            $update_stock_sql = "UPDATE stock SET quantity='$new_stock_quantity' WHERE itemId='$stock_id'";

            if (!mysqli_query($conn, $update_stock_sql)) {
                echo "Failed to update stock for product ID: $product_id. Error: " . mysqli_error($conn);
            }
        } else {
            echo "Insufficient stock for product ID: $product_id.";
        }
    }
} else {
    echo "No items found in the cart for user ID: $userid.";
}




// Include sendmail.php for email notification
include 'login/sendmail.php';

// Function to generate and download PDF
function generateAndDownloadPDF()
{
    $dompdf = new Dompdf();
    ob_start();

    // Include the PHP file (assuming it generates HTML content)
    include 'Reset.php';

    // Get the content generated by the PHP file
    $html = ob_get_clean();

    // Load HTML content into dompdf
    $dompdf->loadHtml($html);

    // Render the HTML as PDF
    $dompdf->render();

    // Save and output the generated PDF
    $output = $dompdf->output();
    file_put_contents('Myreset.pdf', $output);

    $dompdf->stream('Myreset.pdf');
    exit(); // Ensure to exit after downloading the PDF
}

if (isset($_POST['submit'])) {
    // Generate PDF when the submit button is clicked
    generateAndDownloadPDF();
}

// Original commented-out code left as is
// if (isset($_POST['order_btn'])) {
//     // Code for order handling...
// }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>checkout</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="cartcss.css">
    <style>
        #reset {
            display: block;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20%;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .flex {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .inputBox {
            margin-bottom: 20px;
        }

        .select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>

<body>

    <!-- <div class='order-message-container' id="reset">
        <div class='message-container'>
            <h3>thank you for shopping!</h3>
            <div class='order-detail'>

                <span>" . $total_product . "</span>
    <span class='total'> total : $" <?php echo $total; ?> "/- </span>
    </div>
    <div class='customer-details'>
        <p> your name : <span>" <?php echo $usrname; ?> "</span> </p>
        <p> your email : <span>"<?php echo $usrEmail; ?>"</span> </p>
        <p> your address : <span>" . $flat . ", " . $city . ", " . $state . ", " . $country . " - " . $pin_code . "</span> </p> -->
    <!-- <p> your payment mode : <span>" . $method . "</span> </p>
        <p>(*pay when product arrives*)</p>
    </div>
    <button onclick="">OK</button>
    <a href='Productpage.php' class='btn'>continue shopping</a>
    </div>
    </div> --> 

    <form action="verify.php" method="post">
        <button type="submit" name="submit" class="btn">Download the Receipt</button>
    </form>
    
    <a href="../Final_Project/Productpage.php" class="btn">Back to shopping</a>
    <script>
        function closepopup() {
            document.getElementById("reset").style.display = 'none';


        }
    </script>

</body>

</html>