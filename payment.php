<?php
// session_start();
session_start();
// print_r($_SESSION);
$_SESSION['Last_orderid'];
$u_id = $_SESSION['userid'];

// $total1 = $_SESSION['PTotal'];
/*eziga main folder wst ketach yalewen file copy arg */
// @include 'db_connc.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_shopping";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$grand_total = 0;



$usrname = "";
$usrEmail = "";
$usrLname = "";

//echo $randomNumber;
echo $u_id;

$cust_info = mysqli_query($conn, "SELECT * FROM customer where userid='$u_id'");
if (mysqli_num_rows($cust_info) > 0) {

    while ($cust = mysqli_fetch_assoc($cust_info)) {

        // $cust_id = $cust['CustomerID'];
        $usrEmail = $cust['userEmail'];
        $usrname = $cust['userFname'];
        $usrLname = $cust['userLname'];
    }
}
echo $usrname;


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
    <link rel="stylesheet" href="cartcss.css?v=<?php echo time() ?>">

</head>

<body>

    <!-- <?php include 'header.php'; ?> -->

    <div class="container">

        <section class="checkout-form">

            <h1 class="heading">complete your order</h1>

            <form action="https://api.chapa.co/v1/hosted/pay" method="post">

                <div class="display-order">
                    <?php
                    $select_cart = mysqli_query($conn, "SELECT * FROM cart join product on Id=productid JOIN stock  on product.Id=stock.itemId where Customerid='$u_id'");
                    $total = 0;

                    if (mysqli_num_rows($select_cart) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                            $total_price = $fetch_cart['sellPrice'] * $fetch_cart['Quantity'];
                            $grand_total = $total += $total_price;
                    ?>
                            <span><?= $fetch_cart['Productname']; ?>(<?= $fetch_cart['Quantity']; ?>)</span>
                    <?php
                        }
                    } else {
                        echo "<div class='display-order'><span>your cart is empty!</span></div>";
                    }
                    ?>
                    <span class="grand-total"> grand total : ETB <?= $grand_total; ?>/- </span>
                </div>

                <div class="flex">
                    <div class="inputBox">
                        <!-- <label for="">Credit options are available to customers aged 18 years
                     and above who meet the necessary
                     credit criteria set by the online shopping platform.</label>
                  <input type="checkbox" name="term" style="display: none;" required id="check"> -->
                        <span>payment method</span>
                        <select name="method" class="select">
                            <!-- <option value=" cash on delivery" class="cash">cash on delivery</option> -->
                            <!-- <option value="credit cart">credit cart</option> -->
                            <option value="Chapa" selected>Chapa</option>
                        </select>

                        <?php

                        $currentDate = date('Y-m-d'); // Retrieves current date in YYYY-MM-DD format
                        $currentTime = date('H:i:s'); // Retrieves current time in HH:MM:SS format
                        $randomNumber = mt_rand(1, PHP_INT_MAX);
                        $txtranm = $username . $randomNumber;

                        ?>
                        <input type="hidden" name="public_key" value="CHAPUBK_TEST-sK2APDMiQ38gD539Ig15jvi8GyoS0rXl" />
                        <input type="hidden" name="tx_ref" value="<?php echo $txtranm ?>" />

                        <input type="hidden" name="amount" value="<?php echo $total; ?>" />
                        <input type="hidden" name="currency" value="ETB" />
                        <input type="hidden" name="email" value="<?php echo $usrEmail; ?>" />
                        <input type="hidden" name="first_name" value="<?= $usrname; ?>" />
                        <input type="hidden" name="last_name" value="<?= $usrLname; ?>" />
                        <input type="hidden" name="title" value="Let us do this" />
                        <input type="hidden" name="description" value="Paying with Confidence with cha" />
                        <input type="hidden" name="logo" value="http://localhost:7880/Final_Project/image/logo.jpg" />
                        <input type="hidden" name="callback_url" value="http://localhost:7880/Final_Project/callback.php" />
                        <input type="hidden" name="return_url" value="http://localhost:7880/Final_Project/verify.php" />
                        <input type="hidden" name="meta[title]" value="test" />

                     
                        <input type="submit" value="Pay now" name="order_btn" class="btn">
                        <?php
                        echo $usrname;
                        ?>
                        <!-- <button type="submit"> Pay now </button> -->
                    </div>
                </div>

            </form>


        </section>

    </div>


</body>

</html>