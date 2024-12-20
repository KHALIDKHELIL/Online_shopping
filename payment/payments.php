<?php
session_start();
/*
if (isset($_COOKIE['total'])) {
  $grandTotal = $_COOKIE['total'];
} else {
  $grandTotal = 0;
}

*/
?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment</title>
  <link rel="stylesheet" href="payment.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100&family=Roboto+Condensed&display=swap" rel="stylesheet" />
  <script src="payment.js"></script>
  <script src="/cart.js"></script>
</head>

<body>
  <!-- navbar -->
  <nav id="navbar">
    <!--add the logo later -->

    <div><a href="../My Market.html" target="_blank"><img src="../image/logo.jpg" style="z-index: 1000; border-radius: 50%; width: 75px; margin: 5px;" alt="logo"></a> </div>&nbsp;

    <div>
      <h1 style="color: darkmagenta;">PAYMENT</h1>
      <p>finalize</p>
    </div>
    <div>
      <h1><a href="address.php"> ADDRESS</a></h1>
      <p>fill your address</p>
      <p></p>
    </div>
  </nav>

  <!-- container below navbar -->
  <div class="heading">
    <!-- <img src="" alt=""> -->
    <p>
      Please use a digital payment method & help us ensure contactless
      delivery for your safety
    </p>
  </div>

  <div class="option">CHOOSE PAYMENT METHOD</div>

  <!-- payment container start -->
  <div id="container">
    <!-- container1 -->
    <div id="container1">
      <p>Credit/Debit Card</p>
      <p>GooglePay</p>
      <p>Net Banking</p>
      <p>Mobile Wallets</p>
      <p>Cash on Delivery</p>
      <p>Gift Card</p>
    </div>


    <div id="container2">
      <form action="payment.php" method="post">
        <p>Credit/Debit Card</p>
        <input placeholder="Card Number" name="cardNumber" />

        <label class="expiry">expiry</label>
        <input type="date" id="start" name="expiryDate" value="2022-07" />
        <button id="btn" type="submit">
          <!-- Change type to "submit" -->
          PAY NOW
        </button>
      </form>
      <p id="suc"></p>
    </div>



    <!-- container3 -->



    <div id="container3">


      <!-- Modify the <div id="total"> section in payment.php -->
      <div id="total">
        <div class="flex1">
          <p>Shipping Charge</p>
          <p>Free</p>
        </div>
        <!--

          <div class="cartsum2">
            <p>Grand Total</p>
            <p class="changing1">$<?php echo $grandTotal; ?></p>
          </div>
        -->


      </div>




    </div>
  

    <!-- payment container end -->

  
  </div>

    <!-- page footer -->
    <div id="footer">
      <div>
        <div>
          <img src="linkings/genuineproducts.png" alt="" />
        </div>
        <div>
          <p><b>GENUINE PRODUCTS</b></p>
          <p>Sourced Directly From Brands</p>
        </div>
      </div>
      <div>
        <div>
          <img src="linkings/delivery.png" alt="" />
        </div>
        <div>
          <p><b>FREE SHIPPING</b></p>
          <p>For Orders Above $499</p>
        </div>
      </div>
    </div>
    <!--footer end-->
</body>

</html>