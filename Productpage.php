<?php
@include 'db_connc.php';
session_start();
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true;
// Check if the logged-in user is an admin
$isadLoggedIn = isset($_SESSION['logged_in'], $_SESSION['username']) && $_SESSION['logged_in'] == true && !empty($_SESSION['username']);


// Check if the user is logged in and retrieve the userid
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
} else {
    $userid = null; // Or handle the case where the user is not logged in
}


$selectedCategoryName = 'my category';
$selectedSubCategoryName = 'my';

// Check if add to cart button was pressed
if (isset($_POST['add_to_cart']) && $isLoggedIn) {
    $product_id = $_POST['product_Id'];
    $product_color = $_POST['Color'];
    $product_Size = $_POST['Size'];
    $product_quantity = 1;

    // Check if the product is already in the cart
    $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE productid = '$product_id' and Customerid ='$userid' ");
    if (!$select_cart) {
        die("Error selecting cart: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($select_cart) > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Product already added to cart',
        ]);
    } else {
        // Insert the product into the cart
        $insert_product = mysqli_query($conn, "INSERT INTO cart (productid, Customerid, Quantity, Color, Size) 
        VALUES('$product_id', '$userid', '$product_quantity','$product_color','$product_Size')");
        if ($insert_product) {
            $cart_query = mysqli_query($conn, "SELECT COUNT(*) AS cart_count FROM cart WHERE Customerid = '$userid'");
            $cart_count = mysqli_fetch_assoc($cart_query)['cart_count'];

            echo json_encode([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cartCount' => $cart_count,
            ]);
        } else {
            die("Error inserting into cart: " . mysqli_error($conn));
        }
    }
    exit;
}

// Handling category and subcategory selection from GET request

if (isset($_GET['search'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_GET['search']);
    $sql4 = "SELECT p.Id,p.productImage,p.Productname,p.ProductDetail,s.sellPrice FROM product p JOIN stock s on p.Id=s.itemId WHERE Productname LIKE '%$searchQuery%' and s.quantity > 0";
    $selectc = mysqli_query($conn, $sql4);
    if (mysqli_num_rows($selectc) > 0) {
        while ($rowp = mysqli_fetch_assoc($selectc)) {
            echo "<div class='box' style='position:relative; margin-top: 0px; margin-bottom:10px; '>
                        <a href='Productdetail.php?Id={$rowp['Id']}' target='_blank'>
                            <div class='img-box'>
                            <img class='images' src='./image/{$rowp['productImage']}'></img>
                            </div>
                        </a>

                        <div class='bottom'>
                            <p>{$rowp['Productname']}</p>
                            <h2>ETB {$rowp['sellPrice']}</h2>
                            <!-- <button class='button' onclick='addtocart('+(i++)+'')'>Add to cart</button> -->





                        </div>
                        <form id='addToCartForm'>
                            <input type='hidden' name='Size' value='M'>
                            <input type='hidden' name='Color' value='Red'>
                            <input type='hidden' name='product_Id' value='{$rowp['Id']}'>
                            <button type='button' class='addtocartbtn'>Add to Cart</button>
                        </form>









                    </div>";
        }
    }
    exit;
    // Handling category and subcategory selection from GET request
} else if (isset($_GET['subcategorynamee'])) {
    $selectedSubCategoryName = $_GET['subcategorynamee'];
    echo " $selectedSubCategoryName";
    // Fetch products based on subcategory
    $sql4 = "SELECT p.Id,p.Productname,p.productImage,s.sellPrice,sb.Subcategoryname FROM product p LEFT JOIN subcatgory sb on Prodsubcatid = sb.Id LEFT JOIN stock s on p.Id=s.itemId
 WHERE Subcategoryname = '$selectedSubCategoryName' and s.quantity > 0";
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['categorynamee'])) {
    // Fetch products based on category
    $selectedCategoryName = $_GET['categorynamee'];

    $sql4 = "SELECT p.Id,p.Productname,p.productImage,s.sellPrice,c.categoryname FROM product p LEFT JOIN category c on ProductCatID = c.Id LEFT JOIN stock s on p.Id=s.itemId
         WHERE categoryname = '$selectedCategoryName' and s.quantity > 0 ";
} else {
    $sql4 = "SELECT p.Id,p.Productname,p.productImage,s.sellPrice FROM product p JOIN stock s on p.Id=s.itemId where s.quantity > 0 ";  // Default query to fetch all products
}

// Execute the query
$selectc = mysqli_query($conn, $sql4);
if (!$selectc) {
    die("SQL Error: " . mysqli_error($conn)); // Display error message
}
?>


</div>



<!DOCTYPE html>

<html>

<head>
    <!-- <link rel="stylesheet" href="cart.css"> -->
    <!-- <link rel="stylesheet" href="Loginpage.css"> -->
    <script src="https://kit.fontawesome.com/92d70a2fd8.js" crossorigin="anonymous"></script>
    <!-- <link rel="icon" href="../Market-Place/image/ብራንድ1.jpg" type="image/x-icon" /> -->
    <script src="https://kit.fontawesome.com/d5a070a972.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="cart.css?v=<?php echo time() ?>">
    <link rel="icon" type="image/jpg" href="../Final_Project/image/ብራንድ.jpg">
    <title>ብራንድ :Product Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle Add to Cart button click
            $(document).on('click', '.addtocartbtn', function(e) {
                e.preventDefault();

                // Find the closest form to the button and serialize it
                var formData = $(this).closest('form').serialize();

                // Send data via AJAX
                $.ajax({
                    url: 'Productpage.php', // Same page for handling AJAX
                    type: 'POST',
                    data: formData + '&add_to_cart=true', // Add a flag to identify AJAX requests
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.success) {
                            // Update cart count dynamically
                            $('#cartCount').text(result.cartCount);
                            alert(result.message); // Show success message
                        } else {
                            alert(result.message); // Show error message
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error adding product to cart: ' + error);
                        console.error(error);
                    }
                });
            });
        });
    </script>


    <style>
        .addtocartbtn {
            width: 100%;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            border: none;
            outline: none;
            color: rgb(255 255 255);
            text-transform: uppercase;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 0.75rem 1.5rem;
            background-color: rgb(33 150 243);
            border-radius: 0.5rem;
            text-shadow: 0px 4px 18px #2c3442;
        }

        .addtocartbtn:hover {
            background-color: #6fc5ff;
            box-shadow: 0 0 20px #6fc5ff50;
        }

        .addtocartbtn:active {
            background-color: #3d94cf;
            transition: all 0.25s;
            -webkit-transition: all 0.25s;
            box-shadow: none;
        }

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

        .detailpop {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            margin-top: 14%;
            margin-left: 50%;
            /* top: 20%;
            left: 10%; */
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.5);

            border: 1px solid #ccc;
            z-index: 9999;
        }

        .detail {
            position: relative;
            background-color: white;
            width: 60%;
            height: 70%;
            margin-left: 15%;
            margin-top: 6%;


        }

        .close {
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
        }

        .search-container {
            display: flex;
            align-items: center;
        }

        .input {
            height: 30px;
            padding: 0 10px;
        }

        .search-btn {
            height: 30px;
            width: 30px;
            background-color: transparent;
            border: none;
        }

        .search-btn:hover {
            cursor: pointer;
        }

        .alert {
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ff4d4d;
            /* Vibrant red for error/attention */
            color: white;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 1.2rem;
            /* Slightly larger text */
            font-family: Arial, sans-serif;
            text-align: center;
            z-index: 1000;
            /* Ensure it appears above other elements */
            display: none;
            /* Initially hidden */
        }

        .alert.show {
            display: block;
            /* Show the alert when the class is added */
        }


        /* .option {
            display: flex;
            flex-direction: row;
            margin-top: 2px;
            margin-left: 25%
        } */
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
                    <img src="image/logo.jpg" alt="logo" class="logo">
                </a>
            </div>
           
            <div class="search-container">
                <input type="text" name="searchQuery" class="input" id="searchInput" autocomplete="off" placeholder="Search products">

                <!-- <button class="search-btn">
                    <i class="fa fa-search fa-lg" aria-hidden="true" style="color: #fff;"></i>
                </button> -->
            </div>

            <nav class="nav-bar">
                <a href="My Market.php" class="nav-link">Home</a>

                <?php
                $select_rows = mysqli_query($conn, "SELECT * FROM customer 
        JOIN cart ON customer.userid = cart.Customerid 
        JOIN product ON productid = Id  
        WHERE userid = '$userid'") or die('query failed');
                $row_count = mysqli_num_rows($select_rows);
                ?>


                <?php if (!$isadLoggedIn):
                ?>
                    <a href="Order History.php" class="nav-link" id="order-history">Order History</a>
                <?php endif; ?>


                <?php if ($isLoggedIn):
                ?>
                    <?php if (!$isadLoggedIn):
                    ?>
                        <a href="./login/edit-profile" class="nav-link" id="edit-profile">Profile</a>

                    <?php endif; ?>
                
                <?php endif; ?>

                <?php if ($isadLoggedIn):
                    ?>
                        <a href="./login/edit-profile" class="nav-link" id="edit-profile">Dashboard</a>

                    <?php endif; ?>

                <?php if ($isLoggedIn):
                ?>
                    <a href="./login/logout.php" class="nav-link" id="log-out">Log Out</a>
                <?php endif; ?>

                <?php if (!$isLoggedIn): // Show the "Register" link only when the user is not logged in 
                ?>
                    <a href="login/index-one.php" class="nav-link" id="login-user">Log In</a>
                <?php endif; ?>


                <a href="cart.php" class="nav-link" id="cart-icon">
                    <i class="fa-sharp fa-solid fa-cart-plus fa-beat"></i>
                    <span id="cartCount"><?php echo $row_count; ?></span>
                </a>
            </nav>

    </header>

    <nav>
        <form action="" method="get" class="cat_">
            <!-- <div class="option"> -->

            <input class="category" type="submit" name="ALL" value="ALL">


            <?php
            $select = mysqli_query($conn, "SELECT * FROM category");
            if (mysqli_num_rows($select) > 0) {
                while ($row = mysqli_fetch_assoc($select)) {

            ?>
                    <div class="drop">
                        <div class="dropdown">

                            <input class="category" type="submit" name="categorynamee" value="<?php echo $row['categoryname']; ?>">


                            <div class="dropdown-content">
                                <?php
                                $categid = $row['Id'];
                                $select2 = mysqli_query($conn, "SELECT * FROM subcatgory where categoryid= '$categid' ");
                                if (mysqli_num_rows($select2) > 0) {
                                    while ($rowsub = mysqli_fetch_assoc($select2)) {

                                ?>

                                        <input class="Subcategory" type="submit" name="subcategorynamee" value="<?php echo $rowsub['Subcategoryname']; ?>">

                                <?php
                                    };
                                };
                                ?>

                            </div>
                        </div>
                    </div>
            <?php
                    //     };
                    // };
                }
            } else {
                echo 'No Category';
            }
            ?>

            <!-- </div> -->



        </form>
        </div>








    </nav>
    </div>



    </nav>



    <main style="background-color: #ffffff;">
        <?php
        if (mysqli_num_rows($selectc) > 0) {

        ?>

            <div id="root">
            <?php
            // $select = mysqli_query($conn, "SELECT * FROM products");




            while ($rowp = mysqli_fetch_assoc($selectc)) {
                // $imageBlob = $rowp['productImage'];
                // $imageBase64 = base64_encode($imageBlob); // Convert BLOB to Base64
                // $imageSrc = 'data:image/jpg;base64,' . $imageBase64; // Set the image source in Base64 format
                echo "<div class='box' style='position:relative; margin-top: 0px; margin-bottom:10px; '>
                    <a href='Productdetail.php?Id={$rowp['Id']}' target='_blank'>
                        <div class='img-box'>
                        <img class='images' src='./image/{$rowp['productImage']}'></img>
                        </div>
                    </a>

                    <div class='bottom'>
                        <p>{$rowp['Productname']}</p>
                        <h2>ETB {$rowp['sellPrice']}</h2>
                        <!-- <button class='button' onclick='addtocart('+(i++)+'')'>Add to cart</button> -->





                    </div>
                    <form id='addToCartForm'>
                        <input type='hidden' name='Size' value='M'>
                        <input type='hidden' name='Color' value='Red'>
                        <input type='hidden' name='product_Id' value='{$rowp['Id']}'>
                        <button type='button' class='addtocartbtn'>Add to Cart</button>
                    </form>









                </div>";
            };
        }; ?>


            </div>


    </main>



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




            <div class="logo-containers" id="logo" style="z-index: 1000; position:absolute; left:50%; display:flex; justify-content:center; align-items:center; text-align:center;"><a href="My Market.php"><img style=" border-radius: 50%;" src="./image/logo.jpg" alt="logo" width="120px"></a> </div>



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
                <p style="padding:5px; line-height: 10px; word-spacing: 1.5px; letter-spacing: 2px; ">Copyrights<sup>&copy;</sup> 2024. All Rights Reserved. <br> Developed with <i class="fas fa-heart" style="color: rgb(222, 27, 27);"></i> by <a target="_blank" href="ABOUTUS.PHP" style="text-decoration: none; color: rgb(144, 144, 229);">OUR TEAM</a></p>
            </div>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Trigger search on typing
            $('#searchInput').on('input', function() {
                const query = $(this).val();
                // alert(query);
                if (query != "") { // Start searching after 3 characters
                    $.ajax({
                        url: 'Productpage.php', // The PHP script to process the search
                        method: 'GET',
                        data: {
                            search: query
                        },
                        success: function(data) {
                            $('#root').html(data); // Update results container
                        },
                        error: function() {
                            $('#root').html('<p>Error fetching results.</p>');
                        }
                    });
                } else {
                    location.reload(); // Clear results if query is too short
                }
            });
        });
    </script>

    <script>
        // Check if the user is logged in using the PHP session
        let loggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

        // Show alert with a larger, styled box
        function showAlert(message) {
            let alertBox = document.createElement('div');
            alertBox.classList.add('alert', 'show');
            alertBox.textContent = message;
            document.body.appendChild(alertBox);

            // Remove the alert after 3 seconds
            setTimeout(() => {
                alertBox.classList.remove('show');
                document.body.removeChild(alertBox);
            }, 2000);
        }

        // Run this when the page has fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Handle "Add to Cart" buttons
            document.querySelectorAll('.addtocartbtn').forEach(button => {
                button.addEventListener('click', function() {
                    if (!loggedIn) {
                        // Show the alert before redirecting
                        showAlert('You need to log in first!');
                        setTimeout(() => {
                            // After 3 seconds, redirect to the login page
                            window.location.href = './login/index-one.php';
                        }, 2000);
                    } else {
                        showAlert('Product added to cart!');
                    }
                });
            });

            // Handle Cart Icon
            document.getElementById('cart-icon').addEventListener('click', function(event) {
                if (!loggedIn) {
                    event.preventDefault();
                    showAlert('You need to log in first!');
                    setTimeout(() => {
                        window.location.href = './login/index-one.php';
                    }, 2000);
                }
            });

            // Handle Order History Link
            document.getElementById('order-history').addEventListener('click', function(event) {
                if (!loggedIn) {
                    event.preventDefault();
                    showAlert('You need to log in first!');
                    setTimeout(() => {
                        window.location.href = './login/index-one.php';
                    }, 2000);
                }
            });
        });
    </script>
    <script>
        // function openProductDetails(productName, productPrice) {
        //   // Show the product details pop-up for the corresponding product
        //   document.getElementById('product-details-popup-' + productName).style.display = 'block';
        // }

        // function closePopup(productName) {
        //   // Hide the product details pop-up for the corresponding product
        //   document.getElementById('product-details-popup-' + productName).style.display = 'none';
        // }

        function openPopup() {
            document.querySelector(".detailpop").style.display = 'block';
        }

        function closePopup() {
            document.querySelector(".detailpop").style.display = 'none';
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

        // function openPopup(productId) {
        //     const detailElement = document.querySelector(`.detail[data-productid="${productId}"]`);
        //     if (detailElement) {
        //         // Retrieve product ID
        //         const retrievedProductId = detailElement.dataset.productid;
        //         // Use the product ID or perform actions as needed
        //         console.log("Retrieved product ID:", retrievedProductId);
        //         // For example, redirect to another page with the product ID
        //         window.location.href = `try.php?Id=${retrievedProductId}`;
        //     }
        // }
    </script>

</body>

</html>