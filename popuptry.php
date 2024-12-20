<?php

// deleted the session_start()
session_start();


$email = $_SESSION['email'];
//kedatabase endiwesd enaregalen, betachegnaw file (db_conn.php)
$sname = "localhost";
$unmae = "root";
$password = "";
$db_name = "online_shopping";

$conn = mysqli_connect($sname, $unmae, $password, $db_name);

if (!$conn) {
    echo "Connection failed!";
}

if (isset($_POST['add_to_cart'])) {

    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $email = $_SESSION['email'];
    $product_quantity = 1;

    $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE name = '$product_name' and Email='$email' ");

    if (mysqli_num_rows($select_cart) > 0) {
        $message[] = 'product already added to cart';
    } else {
        $insert_product = mysqli_query($conn, "INSERT INTO `cart`(name, price, image, quantity, Email) VALUES('$product_name', '$product_price', '$product_image', '$product_quantity','$email')");
        $message[] = 'product added to cart succesfully';
    }
}

if (isset($_POST['All'])) {

    $sql = "SELECT * FROM products";
} else
if (isset($_POST['Electronics'])) {

    $sql = "SELECT * FROM products WHERE category='Electronics'";
} elseif (isset($_POST['Cloth_&_shoes'])) {
    $sql = "SELECT * FROM products WHERE Category ='Clothes'";
} elseif (isset($_POST['Cosmetics'])) {
    $sql = "SELECT * FROM products WHERE category='Cosmetics'";
} else {
    $sql = "SELECT * FROM products";
}
$select = mysqli_query($conn, $sql);

?>


<!DOCTYPE html>

<html>

<head>
    <!-- <link rel="stylesheet" href="cart.css"> -->
    <!-- <link rel="stylesheet" href="Loginpage.css"> -->
    <script src="https://kit.fontawesome.com/92d70a2fd8.js" crossorigin="anonymous"></script>
    <link rel="icon" href="../Market-Place/image/ብራንድ1.jpg" type="image/x-icon" />
    <script src="https://kit.fontawesome.com/d5a070a972.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="cart.css?v=<?php echo time() ?>">
    <title>ብራንድ</title>

    <style>
        /* Style for the dropdown container */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        /* Style for the dropdown button */
        /* .category {
      background-color: #3498db;
      color: #fff;
      padding: 2px 2px;
      border: none;
      cursor: pointer;
    } */

        /* Style for the dropdown content (hidden by default) */
        .dropdown-content {
            display: none;

            position: relative;
            background-color: #fcf9f9ee;
            width: 100px;
            height: 60px;
            box-shadow: 0 8px 16px 0 rgb(255, 255, 255);
            z-index: 1;
        }

        /* Style for the dropdown items */
        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        /* Hover effect for dropdown items */
        .category:hover .dropdown-content {
            display: flex;
            flex-direction: column;
        }

        .cat_ {
            height: 100px;

        }

        nav {
            height: 120px;
        }

        .option {
            margin-top: 2px;
            margin-left: 25%
        }

        .popdetail {
            display: none;
            position: absolute;
            top: 7%;
            left: 15%;
            right: 15%;
            bottom: 7%;
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body>

    <?php

    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" ><span>' . $message . '</span> <i class="fas fa-times" onclick=" this.parentElement.style.display = `none`;" ></i> </div>';
        };
    };

    ?>

    <header class="header">
        <div class="header-container">
            <div class="logo-container">
                <a href="../My Market.php">
                    <img src="../image/logo.jpg" alt="logo" class="logo">
                </a>
            </div>

            <nav class="nav-bar">
                <a href="My Market.php" class="nav-link">Home</a>
                <!-- <a href="login/register.php" class="nav-link">Register</a>
        <a href="login/index.php" class="nav-link">Login</a> -->
                <?php
                $select_rows = mysqli_query($conn, "SELECT * FROM cart where Email='$email'") or die('query failed');
                $row_count = mysqli_num_rows($select_rows);

                ?>

                <a href="cart.php" class="nav-link"><i class="fa-sharp fa-solid fa-cart-plus fa-beat"></i> <span><?php echo $row_count; ?></span> </a>


    </header>

    <nav>
        <form action="" method="post" class="cat_">
            <!-- <input type="submit" class="category" name="All" value="All"><span>h</span>&nbsp;&nbsp;&nbsp;
      <input type="submit" class="category" name="Electronics" value="Electronics">&nbsp;&nbsp;&nbsp;
      <input type="submit" class="category" name="Cloth_&_shoes" value="Clothes">&nbsp;&nbsp;&nbsp;
      <input type="submit" class="category" name="Cosmetics" value="Cosmetics">&nbsp;&nbsp;&nbsp; -->

            <div class="option">
                <input class="category" type="submit" name="All" value="ALL">



                <div class="dropdown">
                    <input class="category" type="submit" name="Electronics" value="Electronics">
                    <div class="dropdown-content">
                        <!-- <form id="electronics_subcategory" name="electronics_subcategory"> -->
                        <input class="Subcategory" type="submit" value="Mobile" name=" Mobile">
                        <input class="Subcategory" type="submit" value="Yablets" name=" Tablets">
                        <input class="Subcategory" type="submit" value="PC" name=" PC">
                        <input class="Subcategory" type="submit" value="Yablets" name=" Tablets">
                        <input class="Subcategory" type="submit" value="PC" name=" PC">

                        <!-- </form> -->
                    </div>

                </div>

                <div class="dropdown">
                    <input class="category" type="submit" name="Cloth_&_shoes" value="cloths">
                    <div class="dropdown-content">
                        <!-- <form id="cloths_subcategory" name="electronics_subcategory"> -->
                        <input class="Subcategory" type="submit" value="Mobile" name=" Mobile">
                        <input class="Subcategory" type="submit" value="Yablets" name=" Tablets">
                        <input class="Subcategory" type="submit" value="PC" name=" PC">

                        <!-- </form> -->
                    </div>
                </div>

                <div class="dropdown">
                    <button class="category" name="Cosmetics">Cosmetics</button>
                    <div class="dropdown-content">
                        <!-- <form id="electronics_subcategory" name="electronics_subcategory"> -->
                        <input class="Subcategory" type="submit" value="Mobile" name=" Mobile">
                        <input class="Subcategory" type="submit" value="Yablets" name=" Tablets">
                        <input class="Subcategory" type="submit" value="PC" name=" PC"><br><br>

                        <!-- </form> -->
                    </div>
                </div>

            </div>


        </form>

        <!-- <div class="SubCat">
      <form id="electronics_subcategory" name="electronics_subcategory">

        <input class="Subcategory" type="submit" value="Mobile" name=" Mobile">
        <input class="Subcategory" type="submit" value="Yablets" name=" Tablets">
        <input class="Subcategory" type="submit" value="PC" name=" PC"><br><br>

      </form>
    </div> -->
    </nav>
    </div>



    </nav>



    <main style="background-color: #ffffff;">
        <?php
        // $select = mysqli_query($conn, "SELECT * FROM products");
        if (mysqli_num_rows($select) > 0) {
        ?>


            <div id="root">
                <?php while ($row = mysqli_fetch_assoc($select)) { ?>




                    <div class='box' style="position:relative; margin-top: 0px; margin-bottom:10px; ">
                        <a href="product_detail.php?id=<?php echo $row['Id']; ?>">
                            <div class='img-box'>
                                <img class='images' src="php admin crud/uploaded_img/<?php echo $row['productImage']; ?>"></img>
                            </div>
                        </a>
                        <div class='bottom'>
                            <p><?php echo $row['Productname']; ?></p>
                            <h2>$<?php echo $row['Price']; ?></h2>


                            <!-- <button class='button' onclick='addtocart("+(i++)+")'>Add to cart</button> -->
                            <form action="" class="productform" method="post">
                                <input type="hidden" name="product_name" value="<?php echo $row['Productname']; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $row['Price']; ?>">
                                <!-- i added this here below
              
              <input type="hidden" name="user-email" value="<?php /*echo $row['Email']; */ ?>">
            -->

                                <input type="hidden" name="product_image" value="<?php echo $row['productImage']; ?>">
                                <input type="submit" class="button" value="add to cart" name="add_to_cart">
                            </form>




                        </div>





                    </div>


            <?php };
            }; ?>


            </div>

    </main>

    <!-- popup detail -->
    <div class="popdetail">
        <p><?php echo $row['Productname']; ?></p>
        <h2>$<?php echo $row['Price']; ?></h2>


    </div>



    <!---! *it starts here -->



    <a href="#">
        <Back class="back-top">Back to top</div>
    </a>


    <footer>
        <div class="footer-wrap">

            <div class="widgetFooter">
                <h4 class="uppercase">useful links</h4>
                <ul id="footerUsefulLink">
                    <li title="About US">
                        <span class="usefulLinksIcons">
                            <i class="far fa-id-card"></i>
                        </span> <!-- HERE ADD THE USEFUL LINKS WHEN FINISHING, DON'T YOU FORGET KHALID :() -->
                        <a href="ABOUTUS.php">&nbsp;About us</a>
                    </li>
                    <li title="Our Team">
                        <span class="usefulLinksIcons">
                            <i class="far fa-handshake"></i>
                        </span>
                        <a href="ABOUTUS.php/#stalk">&nbsp;Our team</a>
                    </li>

                    <li title="Contact Us">
                        <span class="usefulLinksIcons">
                            <i class="far fa-envelope"></i>
                        </span>
                        <a target="_blank" href="mailto:khalidkhelil19@gmail.com">&nbsp;Contact us</a>
                    </li>
                </ul>
            </div>




            <div class="logo-container" style="z-index: 1000; position:absolute; left:50%; display:flex; justify-content:center; align-items:center; text-align:center;"><a href="My Market.php"><img style=" border-radius: 50%;" src="../image/logo.jpg" alt="logo" width="120px"></a> </div>



            <div class="widgetFooter right">
                <h4 class="uppercase">Social media links</h4>
                <ul id="footerMediaLinks">
                    <li class="media1" title="Facebook">
                        <span class="mediaLinksIcons fb">
                            <i class="fab fa-facebook-square"></i>
                        </span>
                        <a class="fb" target="_blank" href="https://www.facebook.com/khalidkhelil">&nbsp;facebook</a>
                    </li>
                    <li class="media2" target="_blank" title="Twitter">
                        <span class="mediaLinksIcons twit">
                            <i class="fab fa-twitter-square"></i>
                        </span>
                        <a class="twit" target="_blank" href="https://www.Twitter.com/khalidkhelil">&nbsp;Twitter</a>
                    </li>
                    <li class="media3" title="Instagram">
                        <span class="mediaLinksIcons insta">
                            <i class="fab fa-instagram"></i>
                        </span>
                        <a class="insta" target="_blank" href="https://www.instagram.com/khalid.khelil">&nbsp;instagram</a>
                    </li>
                    <li class="media4" title="Github">
                        <span class="mediaLinksIcons git">
                            <i class="fab fa-github-alt"></i>
                        </span>
                        <a target="_blank" class="git" href="https://www.github.com/khalidkhelil">&nbsp;Github</a>
                    </li>
                </ul>
            </div>


        </div>




        <div class="footerCopy">
            <div class="inb">
                <p style="padding:5px; line-height: 10px; word-spacing: 1.5px; letter-spacing: 2px; ">Copyrights<sup>&copy;</sup> 2023. All Rights Reserved. <br> Developed with <i class="fas fa-heart" style="color: rgb(222, 27, 27);"></i> by <a target="_blank" href="ABOUTUS.PHP" style="text-decoration: none; color: rgb(144, 144, 229);">OUR TEAM</a></p>
            </div>
        </div>
    </footer>

    <script>
        // function openProductDetails(productName, productPrice) {
        //   // Show the product details pop-up for the corresponding product
        //   document.getElementById('product-details-popup-' + productName).style.display = 'block';
        // }

        // function closePopup(productName) {
        //   // Hide the product details pop-up for the corresponding product
        //   document.getElementById('product-details-popup-' + productName).style.display = 'none';
        // }

        function openup() {
            document.querySelector(".SubCat").style.display = 'block';
        }

        function closeup() {
            document.querySelector(".popup").style.display = 'none';
        }

        // JavaScript code to show/hide subcategory options

        document.querySelector(".cat_").addEventListener("submit", function(event) {
            const selectedCategory = event.target.value;
            const clothShoesSubcategory = document.getElementById('cloth_shoes_subcategory');
            const electronicsSubcategory = document.querySelector(".SubCat");

            if (selectedCategory === "Cloth_&_shoes") {
                clothShoesSubcategory.style.display = "block";
                electronicsSubcategory.style.display = "none";
            } else if (selectedCategory === "Electronics") {
                clothShoesSubcategory.style.display = "none";
                electronicsSubcategory.style.display = "flex";
            } else {
                clothShoesSubcategory.style.display = "none";
                electronicsSubcategory.style.display = "none";
            }
        });
    </script>


</body>

</html>