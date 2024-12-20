<?php
include 'Security.php';

$userid = $_SESSION['userid'];
error_reporting(E_ALL);
/*eziga main folder wst ketach yalewen file copy arg */
@include 'db_connc.php';




if (isset($_POST['order_btn'])) {


    $userid = $_SESSION['userid'];
    $method = $_POST['method'];
    // $flat = $_POST['flat'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    // $pin_code = $_POST['pin_code'];
    $kebele = $_POST['Kebele'];
    $Phone_no = $_POST['Phone_no'];
    $Status = $_POST["Order_status"];
    if (!preg_match('/^[0-9]{10}$/', $Phone_no)) {
        
        echo "<script>
        alert('Invalid phone number. Please enter a 10-digit Positive number.');
       
    </script>";
    }else{
    
    

    $insert_order_query = mysqli_query($conn, "INSERT INTO customorder(userid, city, Kebele, country,states,Payment_Method,Order_Status, Phone_Number) 
   VALUES ('$userid', '$city', '$kebele', '$country', '$state', '$method','$Status', '$Phone_no')");

    if ($insert_order_query) {
        $order_id = mysqli_insert_id($conn); // Get the inserted order ID
        $_SESSION['Last_orderid'] = $order_id;

        $cart_query = mysqli_query($conn, "SELECT * FROM customer join cart on userid=Customerid
   join product on Id=productid JOIN stock  on product.Id=stock.itemId where Customerid='$userid'");
        $price_total = 0;
        $i = 0;
        if (mysqli_num_rows($cart_query) > 0) {
            while ($product_item = mysqli_fetch_assoc($cart_query)) {
                $product_name[] = $product_item['Productname'] . ' (' . $product_item['Quantity'] . ') ';
                $product_price = $product_item['sellPrice'] * $product_item['Quantity'];
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

        $orderId = mysqli_insert_id($conn);
        echo "Last Inserted Order ID: " . $orderId;
        //inserted last part}
        if ($method == "Chapa") {
            header("Location: ../Final_Project/payment.php");
        } else {
            // echo '<script type="text/javascript">alert("You Ordered Succefully!");</script>';
            // // header("Location: ../Final_Project/Productpage.php");
            // echo '<script type="text/javascript">showOrderMessage();</script>';

            if ($cart_query && $detail_query && $insert_order_query) {
                // $_SESSION['PTotal'] = $price_total;
                echo "

      <div class='order-message-container' id='orderMessageContainer'>
      <div class='message-container'>
         <h3>thank you for shopping!</h3>
         <div class='order-detail'>

            <span>" . $total_product . "</span>
            <span class='total'> total : $" . $price_total . "/-  </span>
         </div>
         <div class='customer-details'>
            <p> your name : <span>" . $username . "</span> </p>
            <p> your email : <span>" . $userEmail . "</span> </p>
            <p> your address : <span>" . $kebele . ", " . $city . ", " . $state . ", " . $country . " - " . $pin_code . "</span> </p>
            <p> your payment mode : <span>" . $method . "</span> </p>
            <button onclick='close()''>Close</button>
            <p>(*pay when product arrives*)</p>
         </div>
            <a href='Productpage.php' class='btn'>continue shopping</a>
         </div>
      </div>
      ";

                $_SESSION['usrname'] = $username;
                $_SESSION['usrEmail'] = $userEmail;
                $_SESSION['usrLname'] = $Lusername;

                //$pdo->lastInsertId();
            }
        }
    }

    ///Into Payment Table

    $amount = $_POST['amount'];
    $currency = $_POST['currency'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $status = $_POST['Status'];
    // $order_id = $id;
    //  $order_id = mysqli_insert_id($conn);
    $OId = $_SESSION['Last_orderid'];
    // Perform the SQL INSERT operation
    // $insert_query = "INSERT INTO payments(first_name, last_name, email, amount, Currency,OrderStatus) 
    // VALUES ('$first_name', '$last_name', '$email', '$amount', '$currency', '$status')";
    $insert_result = mysqli_query($conn, "INSERT INTO payments ( PUserid,POrderId, amount, Currency,OrderStatus) 
    VALUES ( '$userid', '$OId','$amount', '$currency', '$status')");


    if ($insert_result) {
        echo "Inserted Successfully";

        //generateAndDownloadPDF();

        // Redirect to cart.php after insertion
        // header("Location: cart.php");
        // exit();
    } else {
        // Display error message if the insertion fails
        echo "Error: " . mysqli_error($conn);
    }
}
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
    
    
    <style>
        .order-message-container {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: scroll;
            overflow-x: hidden;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1100;
            background-color: var(--dark-bg);
            width: 100%;
        }

        .order-message-container::-webkit-scrollbar {
            width: 1rem;
        }

        .order-message-container::-webkit-scrollbar-track {
            background-color: var(--dark-bg);
        }

        .order-message-container::-webkit-scrollbar-thumb {
            background-color: var(--blue);
        }

        .order-message-container .message-container {
            width: 50rem;
            background-color: var(--white);
            border-radius: .5rem;
            padding: 2rem;
            text-align: center;
        }

        .order-message-container .message-container h3 {
            font-size: 2.5rem;
            color: var(--black);
        }

        .order-message-container .message-container .order-detail {
            background-color: var(--bg-color);
            border-radius: .5rem;
            padding: 1rem;
            margin: 1rem 0;
        }

        .order-message-container .message-container .order-detail span {
            background-color: var(--white);
            border-radius: .5rem;
            color: var(--black);
            font-size: 2rem;
            display: inline-block;
            padding: 1rem 1.5rem;
            margin: 1rem;
        }

        .order-message-container .message-container .order-detail span.total {
            display: block;
            background: var(--red);
            color: var(--white);
        }

        .order-message-container .message-container .customer-details {
            margin: 1.5rem 0;
        }

        .order-message-container .message-container .customer-details p {
            padding: 1rem 0;
            font-size: 2rem;
            color: var(--black);
        }

        .order-message-container .message-container .customer-details p span {
            color: var(--blue);
            padding: 0 .5rem;
            text-transform: none;
        }
    </style>
    

</head>

<body>

    <!-- <?php include 'header.php'; ?> -->

    <div class="container">

        <section class="checkout-form">

            <h1 class="heading">complete your order</h1>

            <form action="" method="post" id="checkoutForm">

                <div class="display-order">
                    <?php
                    $select_cart = mysqli_query($conn, "SELECT * FROM cart join product on Id=productid JOIN stock  on product.Id=stock.itemId where Customerid='$userid'");
                    $total = 0;
                    $grand_total = 0;

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
                        <!-- <span>payment method</span> -->
                        <select name="method" class="select"  onchange="onPaymentMethodChange()" required>
                            <option value="" disabled selected>Select a Payment method</option>
                            <!-- <option value="cash on delivery" class="cash">cash on delivery</option> -->
                            <!-- <option value="credit cart">credit cart</option> -->
                            <option value="Chapa" selected>Chapa</option>
                        </select>

                    </div>
                    <!-- <div class="inputBox" style="display: none;" id="idphoto">
                  <span>National ID</span>
                  <input type="image" placeholder="e.g. picture" name="idphoto" required>
                  </div> -->

                    <!-- <div class="inputBox">
                        <span>address line</span>
                        <input type="text" placeholder="e.g. flat no." name="flat" required>
                    </div> -->
                    <div class="inputBox">
        <!-- <label for="citySelect">City:</label> -->
        <select name="city" id="citySelect" required>
            <option value="" disabled selected>Select a City</option>
            <option value="Addis Abeba" data-id="1">Addis Abeba</option>
            <option value="Adama" data-id="2">Adama</option>
            <option value="Bahirdar" data-id="3">Bahirdar</option>
            <option value="Mekele" data-id="4">Mekele</option>
            <option value="Jigjiga" data-id="5">Jigjiga</option>
            <option value="Hawasa" data-id="6">Hawasa</option>
            <option value="Harrer" data-id="7">Harrer</option>
            <option value="Arbaminch" data-id="8">Arbaminch</option>
        </select>
    </div>

    <!-- State Dropdown -->
    <div class="inputBox">
        <!-- <label for="stateSelect">State/Region:</label> -->
        <select name="state" id="stateSelect" required>
            <option value="" disabled selected>Select a State</option>
            <option value="Addis Abeba" data-id="1">Addis Abeba</option>
            <option value="Oromia" data-id="2">Oromia</option>
            <option value="Amhara" data-id="3">Amhara</option>
            <option value="Tigray" data-id="4">Tigray</option>
            <option value="Somali" data-id="5">Somali</option>
            <option value="Southern Nation" data-id="6">Southern Nation</option>
            <option value="Hareri" data-id="7">Hareri</option>
            <option value="Peoples Region (SNNPR)" data-id="8">Peoples Region (SNNPR)</option>
        </select>
    </div>

    <script>
    // DOM Elements
    const citySelect = document.getElementById("citySelect");
    const stateSelect = document.getElementById("stateSelect");

    // Map of cities to their corresponding states
    const cityToStateMap = {
        "Addis Abeba": "Addis Abeba",
        "Adama": "Oromia",
        "Bahirdar": "Amhara",
        "Mekele": "Tigray",
        "Jigjiga": "Somali",
        "Hawasa": "Southern Nation",
        "Harrer": "Hareri",
        "Arbaminch": "Peoples Region (SNNPR)"
    };

    // Event listener for city selection change
    citySelect.addEventListener("change", function () {
        const selectedCity = citySelect.value; // Get selected city name
        const correspondingState = cityToStateMap[selectedCity]; // Find the corresponding state

        // Set the state dropdown to match the city
        for (let option of stateSelect.options) {
            if (option.value === correspondingState) {
                stateSelect.value = correspondingState;
                break;
            }
        }
    });


    
</script>

            

                    <div class="inputBox">
                        <!-- <span>country</span>
                        <input type="text" placeholder="e.g. Ethiopia" name="country" required> -->
                        <select name="country" id="options" required>
                            <!-- <option value="" disabled selected>Select Country</option> -->
                            <option value="Ethiopia" selected>Ethiopia</option>
                            <!-- <option value="Kenya">Kenya </option>
                            <option value="Ethiopia">Eritrea</option> -->
                        </select>
                    </div>

                    <div class="inputBox">
                        <span>Phone Number</span>
                        <input type="number" placeholder="0910.."  name="Phone_no"   id="phoneNumber"  required>
                        <small id="phoneError" style="color: red; display: none;">Invalid phone number. Only positive numbers with 10 digits are allowed.</small>
                    </div>
                    <div class="inputBox">
                        <span>Kebele</span>
                        <input type="text" placeholder="e.g. 01 or goro kebele" name="Kebele" required>
                    </div>

                    <!-- <div class="inputBox">
                        <span>pin code</span>
                        <input type="number" placeholder="e.g. 123456" name="pin_code" required> -->
                        <!-- <input type="hidden" name="Order status" value="pending"> -->
                    <!-- </div> -->
                    <div class="inputBox">

                        <input type="hidden" name="Order_status" value="pending">
                    </div>
                </div>
                <script>
                    const form = document.getElementById("checkoutForm");
    const phoneNumberInput = document.getElementById("phoneNumber");
    const phoneError = document.getElementById("phoneError");

    // Event listener for phone number input
    phoneNumberInput.addEventListener("input", function () {
        const phoneValue = phoneNumberInput.value;

        // Regex to check for valid phone numbers (only digits, max 10)
        const phoneRegex = /^[0-9]{0,10}$/;

        if (!phoneRegex.test(phoneValue)) {
            phoneError.style.display = "block"; // Show error
            phoneNumberInput.style.borderColor = "red"; // Add a red border
        } else {
            phoneError.style.display = "none"; // Hide error
            phoneNumberInput.style.borderColor = ""; // Reset border color
        }
    });

    // Event listener to check the final phone number on form submission
    phoneNumberInput.addEventListener("blur", function () {
        if (phoneNumberInput.value.length !== 10) {
            phoneError.textContent = "Phone number must be exactly 10 digits.";
            phoneError.style.display = "block";
            phoneNumberInput.style.borderColor = "red";
        } else {
            phoneError.style.display = "none";
            phoneNumberInput.style.borderColor = "";
        }
    });

     // Attach event listener to validate on input
     phoneNumberInput.addEventListener("input", validatePhoneNumber);

// Prevent form submission if the phone number is invalid
form.addEventListener("submit", function (e) {
    if (!validatePhoneNumber()) {
        e.preventDefault(); // Stop the form from submitting
        alert("Please enter a valid phone number before submitting.");
    }
});
</script>
                <?php

                $usrname = "";
                $usrEmail = "";
                $usrLname = "";


                //echo $randomNumber;
                echo $userid;

                $cust_info = mysqli_query($conn, "SELECT * FROM customer where userid='$userid'");
                if (mysqli_num_rows($cust_info) > 0) {

                    while ($cust = mysqli_fetch_assoc($cust_info)) {

                        // $cust_id = $cust['CustomerID'];
                        $usrEmail = $cust['userEmail'];
                        $usrname = $cust['userFname'];
                        $usrLname = $cust['userLname'];
                    }
                }
                ?>


                <input type="hidden" name="amount" value="<?php echo $grand_total; ?>" />
                <input type="hidden" name="currency" value="ETB" />
                <input type="hidden" name="email" value="<?php echo $usrEmail; ?>" />
                <input type="hidden" name="first_name" value="<?= $usrname; ?>" />
                <input type="hidden" name="last_name" value="<?= $usrLname; ?>" />
                <input type="hidden" name="Status" value="UnPayed" />


                <?php
                // $currentDate = date('Y-m-d'); // Retrieves current date in YYYY-MM-DD format
                // $currentTime = date('H:i:s'); // Retrieves current time in HH:MM:SS format
                // // if ($cart_query && $detail_query && $insert_order_query) {
                ?>


                <input type="submit" value="order now" name="order_btn" class="btn">

            </form>

        </section>

    </div>

    <!-- custom js file link  -->
    <script src="js/script.js">
        // function close() {
        //     document.querySelector("order-message-container").style.display = 'none';

        // }
    </script>

</body>

</html>