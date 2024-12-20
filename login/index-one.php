<?php
// Start the session
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database configuration file
include "config.php";

// Redirect users if already logged in
if (isset($_SESSION['user_id']) || isset($_SESSION['username'])) {
    if (isset($_SESSION['username'])) {
        // Admin is logged in
        header("Location: ../inventory/admin.php");
    } else {
        // Regular user is logged in
        header("Location: ../Productpage.php");
    }
    exit();
}

$msg = "";

// Account verification logic
if (isset($_GET['verification'])) {
    $verificationCode = $_GET['verification'];
    $stmt = $conn->prepare("SELECT * FROM customer WHERE userCode = ?");
    $stmt->bind_param("s", $verificationCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $updateStmt = $conn->prepare("UPDATE customer SET userCode = '', verified = 1 WHERE userCode = ?");
        $updateStmt->bind_param("s", $verificationCode);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            $msg = "<div class='alert alert-success'>Account verification successfully completed.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Unable to verify your account. Please try again later.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>Invalid verification code.</div>";
    }
}

// Login logic
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        $msg = "<div class='alert alert-danger'>Email/Username is required.</div>";
    } elseif (empty($password)) {
        $msg = "<div class='alert alert-danger'>Password is required.</div>";
    } else {
        // Admin login check
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();

            // Verify password
            if (sha1($password) === $admin['password']) {
                if ($admin['status'] === 1) {
                    // Set admin session variables
                    $_SESSION['user_id'] = $admin['id'];
                    $_SESSION['username'] = $admin['username'];
                    $_SESSION['logged_in'] = true;

                    // Redirect to admin dashboard
                    header("Location: ../inventory/home.php");
                    exit();
                } else {
                    $msg = "<div class='alert alert-danger'>Your admin account is deactivated. Contact the administrator.</div>";
                }
            } else {
                $msg = "<div class='alert alert-danger'>Invalid username or password.</div>";
            }
        } else {
            // Regular user login check
            $stmt = $conn->prepare("SELECT * FROM customer WHERE userEmail = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $userstatus = $user['customerstatus'];
                $dbPassword = $user['userPassword'];
                if($userstatus == 1 ){

                if (password_verify($password, $dbPassword)) {
                    if ($user['verified'] == 1) {
                        // Set user session variables
                        $_SESSION['userid'] = $user['userid'];
                        $_SESSION['email'] = $user['userEmail'];
                        $_SESSION['firstname'] = $user['userFname'];
                        $_SESSION['logged_in'] = true;

                        // Redirect to product page
                        header("Location: ../Productpage.php");
                        exit();
                    } else {
                        $msg = "<div class='alert alert-info'>Please verify your account before logging in.</div>";
                    }
                } else {
                    $msg = "<div class='alert alert-danger'>Invalid email or password.</div>";
                }
            }{
                $msg = "<div class='alert alert-danger'>This User Is BLocked.</div>";
            }
            } else {
                $msg = "<div class='alert alert-danger'>No account found with that email address.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>
</head>

<body>
    <section class="w3l-mockup-form">
        <div class="container">
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                   
                    <div class="w3l_form align-self">
                        <div class="left_grid_info">
                            <img src="images/image3.svg" alt="">
                        </div>
                    </div>
                    <div class="content-wthree">
                        <h2>Login</h2>
                        <p>Welcome back! Please login to your account.</p>
                        <?php echo $msg; ?>
                        <form action="" method="post">
                            <input type="text" name="email" placeholder="Enter Email/Username" required>
                            <input type="password" name="password" placeholder="Enter Your Password" required>
                            <p><a href="forgot-password.php">Forgot Password?</a></p>
                            <button name="submit" class="btn" type="submit">Login</button>
                        </form>
                        <div class="social-icons">
                            <p>Don't have an account? <a href="register.php">Register Now</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
