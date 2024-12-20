<?php
// Your existing code...

if (isset($_POST['order_btn'])) {
    // Retrieve customer and address information

    // Perform the insertion into the customerorder table
    $insert_order_query = mysqli_query($conn, "INSERT INTO customerorder (userid, city, Kebele, country, State, Pin_code, Payment_Method, Phone_Number) 
        VALUES ('$userid', '$city', '$kebele', '$country', '$state', '$pin_code', '$method', '$Phone_no')");

    if ($insert_order_query) {
        $order_id = mysqli_insert_id($conn); // Get the inserted order ID

        $cart_query = mysqli_query($conn, "SELECT * FROM cart JOIN products ON Id = productid WHERE Customerid = '$userid'");
        if (mysqli_num_rows($cart_query) > 0) {
            while ($product_item = mysqli_fetch_assoc($cart_query)) {
                $product_id = $product_item['productid'];
                $product_price = $product_item['Price'] * $product_item['Quantity'];

                // Insert each product into the order_details table
                $insert_order_details = mysqli_query($conn, "INSERT INTO order_details (order_id, product_id, quantity, product_price) 
                    VALUES ('$order_id', '$product_id', '{$product_item['Quantity']}', '$product_price')");

                if (!$insert_order_details) {
                    // Handle failed insertion of order details
                    die('Failed to insert order details.');
                }
            }
        }
        // Rest of your code to display order confirmation goes here...
    } else {
        // Handle failed insertion of order
        die('Failed to create order.');
    }
}
// Remaining HTML and JavaScript code...
