<?php
// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "market_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['submit'])) {
    // Process form data
    $country = $_POST["ethio"];
    $fname = $_POST["name"];
    $number = $_POST["mobile"];
    $code = $_POST["code"];
    $address = $_POST["address"];


    $sql = "INSERT INTO addresses (country, names, phone_number, code, addresses)
        VALUES ('$country', '$fname', '$number', '$code', '$address')";
    $conn->query($sql);



    if ($conn->affected_rows > 0) {
        // Redirect to the success page
        header("Location: orderplaced.html");
    } else {
        // Redirect to an error page
        header("Location: error.html");
    }
}





$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Address</title>
    <!-- <link rel="icon" href="//www.nykaa.com/media/favicon/default/nykaa_favicon_a.png" type="image/x-icon" /> -->
    <link rel="stylesheet" href="address.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100&family=Roboto+Condensed&display=swap" rel="stylesheet" />

    <script src="address.js"></script>
</head>

<body>
    <!-- navbar -->
    <nav id="navbar">
        <div class="logo-container" style="position: fixed; margin: 5px;"><a href="../My Market.php"><img style="z-index: 1000; border-radius: 50%;" src="../image/logo.jpg" alt="logo" width="60px"></a> </div>&nbsp;

        <div style="margin-left: 5%">
            <h1 class="pink" style="color: darkmagenta;">ADDRESS</h1>
            <p>fill your address</p>
            <p></p>
        </div>

    </nav>
    <!-- container below navbar -->
    <div id="container">
        <!-- container1 -->
        <div id="container1">
            <p>New Address</p>
        </div>

        <!-- container2 -->
        <div id="container2">
            <form id="form" method="post" action="orderplaced.php">
                <label>New Address</label>
                <select id="ethio" placeholder="Select Country" type="text" name="ethio" required>
                    <option value="0">Select Country</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="Afghanistan">Afghanistan</option>
                    <option value="Australia">Australia</option>
                    <option value="Brazil">Brazil</option>
                    <option value="China">China</option>
                    <option value="Egypt">Egypt</option>
                    <option value="England">England</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="France">France</option>
                    <option value="Germany">Germany</option>
                    <option value="India">India</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Palestine">Palestine</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Sudan">Sudan</option>
                    <option value="United States">United States</option>

                    <!-- Add more options for other countries -->
                </select>
                <input id="name" placeholder="Name" type="text" name="name" required />
                <input id="mobile" placeholder="Phone Number" type="number" name="mobile" required />
                <input id="code" placeholder="Postal Code" type="number" name="code" required />
                <input id="address" placeholder="Address" type="text" name="address" required />
                <span id="checkbox"><input class="check" type="checkbox" />
                    <label>Use this as my default Shipping Address</label>
                </span>
                <input id="submit" type="submit" name="submit" value="SHIP TO THIS ADDRESS" />
            </form>
        </div>

        <!-- container3 -->
        <div id="container3" style="background-color: transparent; border:none;">

            <!-- Grand total section -->

        </div>
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