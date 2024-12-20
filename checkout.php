<?php
session_start();
$userid = $_SESSION['userid'];
/*eziga main folder wst ketach yalewen file copy arg */
@include 'db_connc.php';



if (isset($_POST['order_btn'])) {


   $userid = $_SESSION['userid'];
   $method = $_POST['method'];
   $flat = $_POST['flat'];
   $city = $_POST['city'];
   $state = $_POST['state'];
   $country = $_POST['country'];
   $pin_code = $_POST['pin_code'];
   $kebele = $_POST['Kebele'];
   $Phone_no = $_POST['Phone_no'];

   $insert_order_query = mysqli_query($conn, "INSERT INTO customorder(userid, city, Kebele, country, State, Pin_code, Payment_Method, Phone_Number) 
   VALUES ('$userid', '$city', '$kebele', '$country', '$state', '$pin_code', '$method', '$Phone_no')");

   if ($insert_order_query) {
      $order_id = mysqli_insert_id($conn); // Get the inserted order ID


      $cart_query = mysqli_query($conn, "SELECT * FROM customer join cart on userid=Customerid
   join products on Id=productid where Customerid='$userid'");
      $price_total = 0;
      $i = 0;
      if (mysqli_num_rows($cart_query) > 0) {
     cx     $product_name[] = $product_item['Productname'] . ' (' . $product_item['Quantity'] . ') ';
            $product_price = $product_item['Price'] * $product_item['Quantity'];
            $price_total += $product_price;
            $product_id = $product_item['productid'];
            $username = $product_item['userFname'];
            $Lusername = $product_item['userLname'];
            $userEmail = $product_item['userEmail'];


            $total_product = implode(', ', $product_name);
            $detail_query = mysqli_query($conn, "INSERT INTO orderdetail
   (Orderid,Productid,Product_Name,Quantity,productPrice)
   VALUES('$order_id','$product_id','$product_name[$i]','{$product_item['Quantity']}',' $product_price')") or die('query failed');
            $i++;
         };
         //  echo  "<span> </span>";
      };
      if ($cart_query && $detail_query && $insert_order_query) {
         echo "

      <div class='order-message-container'>
      <div class='message-container'>
         <h3>thank you for shopping!</h3>
         <div class='order-detail'>
        
            <span>" . $total_product . "</span>
            <span class='total'> total : $" . $price_total . "/-  </span>
         </div>
         <div class='customer-details'>
            <p> your name : <span>" . $username . "</span> </p>
            <p> your email : <span>" . $userEmail . "</span> </p>
            <p> your address : <span>" . $flat . ", " . $city . ", " . $state . ", " . $country . " - " . $pin_code . "</span> </p>
            <p> your payment mode : <span>" . $method . "</span> </p>
            <p>(*pay when product arrives*)</p>
         </div>
            <a href='Productpage.php' class='btn'>continue shopping</a>
         </div>
      </div>
      ";

         //$pdo->lastInsertId();
      }
      $orderId = mysqli_insert_id($conn);
      echo "Last Inserted Order ID: " . $orderId;

   

   }

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

</head>

<body>

   <!-- <?php include 'header.php'; ?> -->

   <div class="container">

      <section class="checkout-form">

         <h1 class="heading">complete your order</h1>

         <form action="https://api.chapa.co/v1/hosted/pay" method="post">

            <div class="display-order">
               <?php
               $select_cart = mysqli_query($conn, "SELECT * FROM cart join products on Id=productid where Customerid='$userid'");
               $total = 0;
               $grand_total = 0;

               if (mysqli_num_rows($select_cart) > 0) {
                  while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                     $total_price = $fetch_cart['Price'] * $fetch_cart['Quantity'];
                     $grand_total = $total += $total_price;
               ?>
                     <span><?= $fetch_cart['Productname']; ?>(<?= $fetch_cart['Quantity']; ?>)</span>
               <?php
                  }
               } else {
                  echo "<div class='display-order'><span>your cart is empty!</span></div>";
               }
               ?>
               <span class="grand-total"> grand total : $<?= $grand_total; ?>/- </span>
            </div>

            <div class="flex">
               <div class="inputBox">
                  <!-- <label for="">Credit options are available to customers aged 18 years
                     and above who meet the necessary
                     credit criteria set by the online shopping platform.</label>
                  <input type="checkbox" name="term" style="display: none;" required id="check"> -->
                  <span>payment method</span>
                  <select name="method" class="select" onchange="onPaymentMethodChange()">
                     <option value="cash on delivery" class="cash">cash on delivery</option>
                     <!-- <option value="credit cart">credit cart</option> -->
                     <option value="paypal" selected>paypal</option>
                  </select>

               </div>
               <!-- <div class="inputBox" style="display: none;" id="idphoto">
                  <span>National ID</span>
                  <input type="image" placeholder="e.g. picture" name="idphoto" required>
               </div> -->

               <div class="inputBox">
                  <span>address line</span>
                  <input type="text" placeholder="e.g. flat no." name="flat" required>
               </div>
               <div class="inputBox">
                  <span>city</span>
                  <input type="text" placeholder="e.g. Adama" name="city" required>
               </div>
               <div class="inputBox">
                  <span>state</span>
                  <input type="text" placeholder="e.g. East shoa" name="state" required>
               </div>
               <div class="inputBox">
                  <span>country</span>
                  <input type="text" placeholder="e.g. Ethiopia" name="country" required>
               </div>
               <div class="inputBox">
                  <span>Phone Number</span>
                  <input type="number" placeholder="+251" name="Phone_no" required>
               </div>
               <div class="inputBox">
                  <span>Kebele</span>
                  <input type="text" placeholder="e.g. 01 or goro kebele" name="Kebele" required>
               </div>

               <div class="inputBox">
                  <span>pin code</span>
                  <input type="number" placeholder="e.g. 123456" name="pin_code" required>
               </div>
            </div>
            <?php

            $currentDate = date('Y-m-d'); // Retrieves current date in YYYY-MM-DD format
            $currentTime = date('H:i:s'); // Retrieves current time in HH:MM:SS format
            if ($cart_query && $detail_query && $insert_order_query) {
            ?>
               <input type="hidden" name="public_key" value="CHAPUBK_TEST-sK2APDMiQ38gD539Ig15jvi8GyoS0rXl" />
               <input type="hidden" name="tx_ref" value="<?php $username + $currentDate ?>" />
               <input type="hidden" name="amount" value="<?php $price_total ?>" />
               <input type="hidden" name="currency" value="ETB" />
               <input type="hidden" name="email" value="<?php $userEmail ?>" />
               <input type="hidden" name="first_name" value="<?php $username ?>" />
               <input type="hidden" name="last_name" value="<?php $Lusername ?>" />
               <input type="hidden" name="title" value="Let us do this" />
               <input type="hidden" name="description" value="Paying with Confidence with cha" />
               <input type="hidden" name="logo" value="https://chapa.link/asset/images/chapa_swirl.svg" />
               <input type="hidden" name="callback_url" value="http://localhost:7880/Final_Project/checkout.php" />
               <input type="hidden" name="return_url" value="http://localhost:7880/Final_Project/mymarket.php" />
               <input type="hidden" name="meta[title]" value="test" />
            <?php
            }


            ?>
            <input type="submit" value="order now" name="order_btn" class="btn">

         </form>

      </section>

   </div>

   <!-- custom js file link  -->
   <script src="js/script.js">
      function onpeninputid() {
         document.getElementById('#check').style.display = 'block';
         document.getElementById('#idphoto').style.display = 'block';

      }

      function onPaymentMethodChange() {
         const method = document.querySelector('select[name="method"]').value;
         const check = document.getElementById('#check');
         const idPhotoDiv = document.getElementById('#idphoto');

         if (method === 'cash on delivery') {
            check.style.display = 'inline-block'; // Show the #check checkbox
            idPhotoDiv.style.display = 'block'; // Show the #idphoto div
         } else {
            check.style.display = 'none'; // Hide the #check checkbox
            idPhotoDiv.style.display = 'none'; // Hide the #idphoto div
         }
      }
   </script>

</body>

</html>