<?php
session_start();
include "db_connc.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // If not logged in, redirect to the login page
    header('Location: index-one.php');
    exit();
}


$u_id = $_SESSION['userid'];
echo $u_id;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            /* Dark-blue background */
            color: #ffffff;
            /* Dark-white text color */
            margin: 0;
            padding: 20px;
        }

        .notification-container {
            width: 80%;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
        }

        .notification-list {
            padding: 20px;
        }

        .notification-item {
            background-color: #1e1e1e;
            /* Dark-blue notification background */
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .order-id {
            font-weight: bold;
        }

        .order-details {
            color: #cccccc;
            /* Light-gray text color */
        }
    </style>
</head>

<body>
    <div class="notification-container">
        <h2>Order History</h2>
        <div class="notification-list">
            <?php
            // PHP code to fetch order history from the database
            // $servername = "localhost";
            // $username = "your_username";
            // $password = "your_password";
            // $dbname = "your_database_name";

            // // Create connection
            // $conn = new mysqli($servername, $username, $password, $dbname);

            // // Check connection
            // if ($conn->connect_error) {
            //     die("Connection failed: " . $conn->connect_error);
            // }


            // SQL query to retrieve order history and combine orders with the same OrderId
            $sql = "SELECT 
customer.userFname, 
customer.userLname, 
GROUP_CONCAT(orderdetail.Product_Name SEPARATOR '<br>') AS Product_Names,
SUM(orderdetail.Quantity) AS Total_Quantity,
SUM(orderdetail.productPrice) AS Total_Price
FROM 
customer 
JOIN 
customorder ON customer.userid = customorder.userid
JOIN 
orderdetail ON customorder.OrderId = orderdetail.Orderid 
WHERE 
customer.userid = '$u_id'
GROUP BY 
customorder.OrderId";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                // Output data of each aggregated order
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='notification-item'>
    <span class='order-id'>User name: " . $row["userFname"] . " " . $row["userLname"] . "</span><br>
    <span class='order-details'><strong>Products:</strong><br>" . $row["Product_Names"] . "</span><br>
    <span class='order-details'><strong>Total Quantity:</strong> " . $row["Total_Quantity"] . "</span><br>
    <span class='order-details'><strong>Total Price:</strong> " . $row["Total_Price"] . "</span>
  </div>";
                }
            } else {
                echo "No orders found";
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>

</html>