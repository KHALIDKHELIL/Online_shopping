<?php

include 'Security.php';
include 'db_connc.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
   // If not logged in, redirect to the login page
   header('Location: ../login/index-one.php');
   exit();
}


$userid = $_SESSION['userid'];


$sql = "DELETE FROM cart WHERE carttimestamp < DATE_SUB(NOW(), INTERVAL 1 DAY)";

// Execute the query
if ($conn->query($sql) === TRUE) {
   echo "Records deleted successfully.";
} else {
   echo "Error deleting records: " . $conn->error;
}


if (isset($_POST['update_update_btn'])) {
   $update_value = $_POST['update_quantity'];
   $update_id = $_POST['update_quantity_id'];
   $itemid = $_POST['item_id'];
   //check if the item is in stock with this quantity
   $quantity_check = mysqli_query($conn, "SELECT * FROM customer join cart on Customerid=userid
         join product on Id=productid JOIN stock on product.Id=stock.itemId  where stock.itemId='$itemid'");
          if (mysqli_num_rows($quantity_check) > 0) {



            while ($quantity = mysqli_fetch_assoc($quantity_check)) {
               $stock_quantity = $quantity['quantity'];
               $productname = $quantity['Productname'];
               
               if($update_value>$stock_quantity){
                  echo "<script>
                  alert('Insufficient stock for product $productname');
                  window.location.href = 'cart.php'; // Redirect to cart.php after the alert
              </script>";
              exit; // Stop further PHP execution
              
               }else{
   $update_quantity_query = mysqli_query(
      $conn,
      "UPDATE cart SET Quantity = '$update_value' WHERE cartid = '$update_id'"
   );
   if ($update_quantity_query) {
      header('location:cart.php');
   };
};};};
};

if (isset($_GET['remove'])) {
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE cartid = '$remove_id'");
   header('location:cart.php');
};

if (isset($_GET['delete_all'])) {
   mysqli_query($conn, "DELETE FROM `cart`");
   header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="cartcss.css">

</head>

<body>

   <!-- <?php include 'header.php'; ?> -->

   <div class="container">

      <section class="shopping-cart">

         <h1 class="heading">shopping cart</h1>

         <table>

            <thead>
               <th>Email</th>
               <th>name</th>
               <th>price</th>
               <th>quantity</th>
               <th>total price</th>
               <th>action</th>
            </thead>

            <tbody>

               <?php

               $select_cart = mysqli_query($conn, "SELECT * FROM customer join cart on Customerid=userid
         join product on Id=productid JOIN stock on product.Id=stock.itemId  where Customerid='$userid'");

               $grand_total = 0;


               if (mysqli_num_rows($select_cart) > 0) {



                  while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {

               ?>

                     <tr>
                        <td><?php echo $fetch_cart['userEmail'] ?></td>
                        <td><?php echo $fetch_cart['Productname']; ?></td>
                        <td>$<?php echo $fetch_cart['sellPrice']; ?></td>
                        <?php

                        $_SESSION['Productname'] = $fetch_cart['Productname'];
                        $_SESSION['Price'] = $fetch_cart['sellPrice'];
                        ?>
                        <td>
                           <form action="" method="post">
                              <input type="hidden" name="update_quantity_id" value=" <?php echo $fetch_cart['cartid']; ?>">
                              <input type="number" name="update_quantity" min="1" value="<?php echo $fetch_cart['Quantity']; ?>">
                              <input type="hidden" name="item_id"  value="<?php echo $fetch_cart['itemId']; ?>">
                              <input type="submit" value="update" name="update_update_btn">
                           </form>
                        </td>
                        <td>ETB<?php echo $sub_total = $fetch_cart['sellPrice'] * $fetch_cart['Quantity']; ?>/-</td> <!-- !here i removed the number_format function -->
                        <td><a href="cart.php?remove= <?php echo $fetch_cart['cartid']; ?>" onclick="return confirm('remove item from cart?')" class="delete-btn"> <i class="fas fa-trash"></i> remove</a></td>

                     </tr>
               <?php

                     $grand_total += $sub_total;
                  };
               };
               ?>
               <tr class="table-bottom">
                  <td><a href="../Final_Project/Productpage.php" class="option-btn " style="margin-top: 0;">continue shopping</a></td>
                  <td colspan="3">grand total</td>
                  <td>ETB<?php echo $grand_total; ?>/-</td>
                  <td><a href="cart.php?delete_all" onclick="return confirm('are you sure you want to delete all?');" class="delete-btn"> <i class="fas fa-trash"></i> delete all </a></td>
               </tr>

            </tbody>

         </table>

         <div class="checkout-btn">
            <a href="checkouttry.php " class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">proceed to checkout</a>
         </div>

      </section>

   </div>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>