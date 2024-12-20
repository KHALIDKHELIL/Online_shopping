<?php

$userid = $_SESSION['userid'];
/*eziga main folder wst ketach yalewen file copy arg */
@include 'db_connc.php';

$cart_query = mysqli_query($conn, "SELECT * FROM customer join cart on userid=Customerid
join product on Id=productid JOIN stock on product.Id=stock.itemId  where Customerid='$userid'");
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
        //         $detail_query = mysqli_query($conn, "INSERT INTO orderdetail
        // (Orderid,Productid,Product_Name,Quantity,productPrice)
        // VALUES('$order_id','$product_id','$product_name[$i]','{$product_item['Quantity']}',' $product_price')") or die('query failed');
        //         $i++;
    };
    //  echo  "<span> </span>";
};
$Order = mysqli_query($conn, "SELECT userid, Payment_Method,city,states, Kebele, country from customorder where userid ='$userid' ");
if (mysqli_num_rows($Order) > 0) {
    while ($row_ordr = mysqli_fetch_array($Order)) {
        $Payment_Method = $row_ordr['Payment_Method'];
        $country = $row_ordr['country'];
        $states = $row_ordr['states'];

        $city = $row_ordr['city'];
        $Kebele = $row_ordr['Kebele'];
    }
}
$currentday = date('Y-m-d ');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/92d70a2fd8.js" crossorigin="anonymous"></script>
    <link rel="icon" href="../Market-Place/image/ብራንድ1.jpg" type="image/x-icon" />
    <script src="https://kit.fontawesome.com/d5a070a972.js" crossorigin="anonymous"></script>

    <title>Document</title>
    <style>
        h1 {
            position: relative;
            left: 25%;
            text-decoration: underline;
        }

        h2 {
            text-decoration: dotted;
        }

        img {
            position: relative;
            left: 40%;
        }

        .all {
            display: flex;
            flex-direction: row;
        }
    </style>
</head>

<body>
    <img style="size: 10%; " src="./image/logo.jpg" alt="">
    <h1>Brand Shopping Center</h1>
    <div class="all">
        <div class="customer">
            <h3>Customer Name : <?php echo $username . " " . $Lusername; ?></h3>
            <h3>Product Name {Quantity} : <?php echo $total_product; ?></h3>
            <h3>User Email : <?php echo $userEmail; ?></h3>

        </div>
        <div class="detail">
            <h2>Customer Detail </h2>
            <h5> PAYMENT METHOD :<?php echo $Payment_Method; ?></h5>
            <h5> COUNTRY :<?php echo $country; ?></h5>
            <h5> State :<?php echo $states; ?></h5>
            <h5> CITY :<?php echo $city; ?></h5>
            <h5> KEBELE :<?php echo $Kebele; ?></h5>

            <h3>Total Price :  <?php echo $price_total; ?> ETB </h3>

        </div>
    </div>
    <h4> Payment Succeed at <?php echo $currentday ?></h4>
    <h3> Prepared By Brand</h3>
</body>

</html>