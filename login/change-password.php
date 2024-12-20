<?php

$msg = "";

include 'config.php';

if (isset($_GET['reset'])) {
    $resetCode = mysqli_real_escape_string($conn, $_GET['reset']);
    // Check if the reset code exists in the database
    $query = mysqli_query($conn, "SELECT * FROM customer WHERE userCode='{$resetCode}'");

    if (mysqli_num_rows($query) > 0) {
        if (isset($_POST['submit'])) {
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm-password'];

            if ($password === $confirm_password) {
                // Hash the new password using password_hash()
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Update the password in the database
                $newCode = md5(rand());
                $updateQuery = mysqli_query($conn, "UPDATE customer SET userPassword='{$hashedPassword}', userCode='{$newCode}' WHERE userCode='{$resetCode}'");


                if ($updateQuery) {
                    header("Location: index-one.php");
                } else {
                    $msg = "<div class='alert alert-danger'>Failed to update password. Please try again.</div>";
                }
            } else {
                $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match.</div>";
            }
        }
    } else {
        $msg = "<div class='alert alert-danger'>Invalid or expired reset link.</div>";
    }
} else {
    header("Location: forgot-password.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Brand - Change Password</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords"
        content="Login Form" />
    <!-- //Meta tag Keywords -->

    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!--/Style-CSS -->
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
    <!--//Style-CSS -->

    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>

</head>

<body>

    <!-- form section start -->
    <section class="w3l-mockup-form">
        <div class="container">
            <!-- /form -->
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                 
                    <div class="w3l_form align-self">
                        <div class="left_grid_info">
                            <img src="images/image3.svg" alt="">
                        </div>
                    </div>
                    <div class="content-wthree">
                        <h2>Change Password</h2>
                       
                        <?php echo $msg; ?>
                        <form action="" method="post">
                            <input type="password" class="password" name="password" placeholder="Enter Your Password" required>
                            <input type="password" class="confirm-password" name="confirm-password" placeholder="Enter Your Confirm Password" required>
                            <button name="submit" class="btn" type="submit">Change Password</button>
                        </form>
                        <div class="social-icons">
                            <p>Back to! <a href="index.php">Login</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- //form -->
        </div>
    </section>
    <!-- //form section start -->

    <script src="js/jquery.min.js"></script>
    <script>
        $(document).ready(function(c) {
            $('.alert-close').on('click', function(c) {
                $('.main-mockup').fadeOut('slow', function(c) {
                    $('.main-mockup').remove();
                });
            });
        });
    </script>

</body>

</html>