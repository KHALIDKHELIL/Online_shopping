<?php
// Include your database connection code here
@include 'db_connc.php';
session_start();
// Get the product ID from the URL
$product_id = $_GET['Id'];
$userid = $_SESSION['userid'];
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_Id'];
    $product_color = $_POST['Color'];
    $product_Size = $_POST['Size'];
    $userid = $_SESSION['userid'];
    $product_quantity = 1;

    $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE productid = '$product_id' and Customerid='$userid' ");

    if (mysqli_num_rows($select_cart) > 0) {
        $message[] = 'product already added to cart';
    } else {
        $insert_product = mysqli_query($conn, "INSERT INTO cart (productid, Customerid, Quantity, Color, Size) 
        VALUES('$product_id', '$userid', '$product_quantity', '$product_color','$product_Size')");
        $message[] = 'product added to cart succesfully';
    }
}

// Fetch the product details by ID
$sql = "SELECT * FROM product JOIN stock on product.Id=stock.itemId WHERE product.Id = $product_id";
$result = mysqli_query($conn, $sql);

if ($result && $product = mysqli_fetch_assoc($result)) {
    // Get the product's rating
    // $rating_sql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE product_id = $product_id";
    // $rating_result = mysqli_query($conn, $rating_sql);
    // $rating = mysqli_fetch_assoc($rating_result);

    // Get product reviews
    // $reviews_sql = "SELECT * FROM reviews WHERE product_id = $product_id";
    // $reviews_result = mysqli_query($conn, $reviews_sql);

?>
    <!DOCTYPE html>
    <html>

    <head>
        <title><?php echo $product['Productname']; ?></title>
        <style>
            body {
                background-color: #f8f9fa;
                color: #333;
                font-family: 'Segoe UI', Arial, sans-serif;
                margin: 0;
                padding: 20px;
                line-height: 1.6;
            }

            .detail {
                max-width: 1200px;
                margin: 0 auto;
                display: flex;
                gap: 40px;
                padding: 20px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .box {
                flex: 1;
            }

            .img-box {
                margin-bottom: 20px;
            }

            .img-box img {
                width: 100%;
                height: auto;
                border: none;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .discription {
                flex: 2;
                position: static;
            }

            h1,
            h2 {
                color: #2c3e50;
                margin-bottom: 20px;
            }

            .productform {
                margin-top: 20px;
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            select,
            input[type="text"] {
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 16px;
                max-width: 300px;
            }

            input[type="submit"] {
                background-color: #1da1f2;
                color: white;
                padding: 12px 24px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                width: auto !important;
                transition: background-color 0.3s;
            }

            input[type="submit"]:hover {
                background-color: #1991db;
            }

            .review {
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
                margin-bottom: 20px;
            }

            .review strong {
                color: #2c3e50;
            }

            /* Enhanced review form styles */
            .review-form {
                background: white;
                padding: 25px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                max-width: 600px;
                margin: 20px auto;
            }

            .review-form div {
                margin-bottom: 20px;
            }

            .review-form label {
                display: block;
                margin-bottom: 8px;
                color: #2c3e50;
                font-weight: 500;
            }

            .review-form textarea {
                width: 100%;
                height: 120px;
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 4px;
                resize: vertical;
            }

            .review-form select {
                width: 100%;
                max-width: 200px;
            }

            .review-form button {
                background-color: #1da1f2;
                color: white;
                padding: 12px 24px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s;
            }

            .review-form button:hover {
                background-color: #1991db;
            }

            /* Message styles */
            .message {
                padding: 10px 20px;
                border-radius: 4px;
                margin-bottom: 20px;
            }

            .success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }

            .error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
        </style>
    </head>

    <body>
        <div class="detail">
            <div class='box' style="position:relative; margin-top: 0px; margin-bottom:10px; ">
                <div class='img-box'>
                    <img src="php admin crud/uploaded_img/<?php echo $product['productImage']; ?>" alt="<?php echo $product['Productname']; ?>">
                </div>
                <!-- <h3>Description</h3>
                <p><?php echo $product['ProductDetail']; ?></p>
                <p>Price: $<?php echo $product['sellPrice']; ?></p> -->


            </div>
            <div class="discription">
                <h2>Description</h2>
                <?php //echo $product['ProductDetail']; 
                ?>
                <p><?php echo $product['ProductDetail']; ?></p>
                <p>Price: $<?php echo $product['sellPrice']; ?></p>
                <form action="" class="productform" method="post">
                    <select id="selectOption" onchange="updateInput(this)">
                        <option value="XL">XL </option>
                        <option value="x">x</option>
                        <option value="xx">xx</option>
                        <option value="xy">xy</option>
                    </select>
                    <input type="text" name="Size" placeholder="Enter the Size" id="inputField" readonly>
                    <input type="text" name="Color" placeholder="Enter the Color">

                    <input type="hidden" name="product_Id" value="<?php echo $product['Id']; ?>">

                    <input type="submit" style="width: 10%;padding-left: 2px;" class="button" value="cart" name="add_to_cart">
                </form>
            </div>

            <!-- <form action="" method="post">
                <input type="text" name="Color" id=>
                <input type="text" name="Size" id=>
                <input type="submit" class="button" value="Add_To_Product" name="Product_detail">


            </form> -->
        </div>
    <?php
}

    ?>

    <h2>Product Rating</h2>
    <?php
    // Fetch average rating
    $avg_rating_sql = "SELECT AVG(Rating) as average_rating 
                   FROM `rating & review` 
                   WHERE Productid = '$product_id'";
    $avg_rating_result = mysqli_query($conn, $avg_rating_sql);
    $avg_rating = mysqli_fetch_assoc($avg_rating_result);

    // Handle null values to avoid deprecated warnings
    $average_rating = $avg_rating['average_rating'] ?? null;

    if ($average_rating !== null) {
        echo "<p>Average Rating: " . number_format($average_rating, 1) . " / 5</p>";
    } else {
        echo "<p>Average Rating: No ratings yet.</p>";
    }
    ?>


    <h2>Product Reviews</h2>
    <?php
    // Fetch all reviews with customer information
    $reviews_sql = "SELECT r.*, c.userFName as customer_name 
                    FROM `rating & review` r 
                    JOIN customer c ON r.userid = c.userId 
                    WHERE r.Productid = '$product_id'";
    $reviews_result = mysqli_query($conn, $reviews_sql);

    if (mysqli_num_rows($reviews_result) > 0) {
        while ($review = mysqli_fetch_assoc($reviews_result)) {
            echo "<div class='review'>";
            echo "<p><strong>" . htmlspecialchars($review['customer_name']) . "</strong></p>";
            echo "<p>Rating: " . $review['Rating'] . "/5</p>";
            echo "<p>Review: " . htmlspecialchars($review['Review']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No reviews yet.</p>";
    }
    ?>
    <h2>Add Review</h2>
    <form method="post" action="" class="review-form">
        <div>
            <label for="rating">Rating:</label>
            <select name="rating" id="rating" required>
                <option value="5">5 - Excellent</option>
                <option value="4">4 - Very Good</option>
                <option value="3">3 - Good</option>
                <option value="2">2 - Fair</option>
                <option value="1">1 - Poor</option>
            </select>
        </div>
        <div>
            <label for="review">Review:</label>
            <textarea name="review" id="review" required></textarea>
        </div>
        <button type="submit" name="submit_review">Submit Review</button>
    </form>

    <?php
    // Handle review submission
    if (isset($_POST['submit_review'])) {
        $rating = mysqli_real_escape_string($conn, $_POST['rating']);
        $review = mysqli_real_escape_string($conn, $_POST['review']);

        // Check if user has already reviewed this product
        $check_review = mysqli_query($conn, "SELECT * FROM `rating & review` 
            WHERE userid = '$userid' AND Productid = '$product_id'");

        if (mysqli_num_rows($check_review) > 0) {
            echo "<p style='color: red;'>You have already reviewed this product!</p>";
        } else {
            $insert_review = mysqli_query($conn, "INSERT INTO `rating & review` 
                (userid, Productid, Rating, Review) 
                VALUES ('$userid', '$product_id', '$rating', '$review')");

            if ($insert_review) {
                echo "<p style='color: green;'>Review submitted successfully!</p>";
                // Refresh the page to show the new review
                echo "<script>window.location.reload();</script>";
            } else {
                echo "<p style='color: red;'>Error submitting review!</p>";
            }
        }
    }
    ?>

    <script>
        function updateInput(selectElement) {
            const inputField = document.getElementById('inputField');
            inputField.value = selectElement.value;
        }
    </script>
    </body>

    </html>