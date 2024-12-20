<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();
if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: welcome.php");
    die();
}
require 'vendor/autoload.php';
include 'config.php';
$msg = "";

if (isset($_POST['submit'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $code = md5(rand());  // Generate a unique code for verification

    function getNextId($conn)
    {
        $result = mysqli_query($conn, "SELECT userID FROM customer ORDER BY userID ASC");
        $usedIds = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $usedIds = array_map(function ($item) {
            return $item['userID'];
        }, $usedIds);

        $id = 1;
        foreach ($usedIds as $usedId) {
            if ($id < $usedId) {
                break;
            }
            $id++;
        }

        return $id;
    }

    $nextID = getNextId($conn);

    // Password validation function
    function validatePassword($password)
    {
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return "Password must contain at least 1 uppercase letter.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            return "Password must contain at least 1 number.";
        }
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return "Password must contain at least 1 special character.";
        }
        return true;
    }

    $passwordValidation = validatePassword($password);
    if ($passwordValidation !== true) {
        $msg = "<div class='alert alert-danger'>$passwordValidation</div>";
    } else {
        $stmt = $conn->prepare("SELECT * FROM customer WHERE userEmail=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $msg = "<div class='alert alert-danger'>{$email} - This email address has already been registered.</div>";
        } else {
            if ($password === $confirm_password) {
                // Hash the password before storing
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Store hashed password and user code in the database
                $stmt = $conn->prepare("INSERT INTO customer (userid, userFname, userLname, userEmail, userPassword, userCode, verified) VALUES (?, ?, ?, ?, ?, ?, 0)");
                $stmt->bind_param("ssssss", $nextID, $firstname, $lastname, $email, $hashedPassword, $code);
                $result = $stmt->execute();

                if ($result) {
                    $mail = new PHPMailer(true);
                    unset($_SESSION['verification_completed']);

                    $subject = 'Verify your account';
                    $body = "
<div style='background-color: #f9f9f9; padding: 20px; border-radius: 8px;'>
<h3 style='color: #2c3e50;'>Welcome to Brand!</h3>
<p style='color: #34495e;'>To verify your account, please click the link below:</p>
<div style='margin-top: 10px;'>
   <a href='http://localhost:7880/Final_Project/login/verify.php?verification=" . $code . "' style='background-color: #2ecc71; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px;'>Verify your email</a>
</div>
<div style='margin-top: 15px;'>
   <p style='color: #3498db;'>Need assistance? Contact our support team at <a href='mailto:support@brand.com' style='color:#3498db; text-decoration: none;'>support@brand.com</a></p>
</div>
</div>
";
                    try {

                        // Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'khalidkhelil19@gmail.com';  // Use your Gmail address
                        $mail->Password = 'umgunlsmnojktcck';  // Use your Gmail app password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use ENCRYPTION_SMTPS for SSL (Port 465)
                        $mail->Port = 587;  // Use 465 for SSL

                        // Recipients
                        $mail->setFrom('khalidkhelil19@gmail.com', 'Account Verification');
                        $mail->addAddress($email);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = $subject;
                        $mail->Body = $body;
                        // Send email
                        $mail->send();
                        $msg = "<div class='alert alert-success'>We have sent you a verification code to your email.</div>";
                    } catch (Exception $e) {
                        $msg = "<div class='alert alert-danger'>Error: Could not send verification email. Please try again later. Mailer Error: {$mail->ErrorInfo}</div>";
                    }
                } else {
                    $msg = "<div class='alert alert-danger'>Error occurred during registration. Please try again.</div>";
                }
            } else {
                $msg = "<div class='alert alert-danger'>Passwords do not match.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Register - Brand</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords" content="Login Form" />
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
                            <img src="images/image2.svg" alt="">
                        </div>
                    </div>
                    <div class="content-wthree">
                        <h2>Register Now</h2>

                        <?php echo $msg; ?>
                        <form action="" method="post">
                            <input type="text" class="name" name="firstname"
                                placeholder="Enter Your First Name"
                                value="<?php if (isset($_POST['submit'])) {
                                            echo $firstname;
                                        } ?>"
                                pattern="[A-Za-z]+"
                                title="Only letters are allowed"
                                required>

                            <input type="text" class="name" name="lastname"
                                placeholder="Enter Your Last Name"
                                value="<?php if (isset($_POST['submit'])) {
                                            echo $lastname;
                                        } ?>"
                                pattern="[A-Za-z]+"
                                title="Only letters are allowed"
                                required>

                            <input type="email" class="email" name="email"
                                placeholder="Enter Your Email"
                                value="<?php if (isset($_POST['submit'])) {
                                            echo $email;
                                        } ?>"
                                required>

                            <input type="password" class="password" name="password"
                                placeholder="Enter Your Password" required>
                            <p id="password-message" style="color: red;"></p>

                            <input type="password" class="confirm-password" name="confirm-password"
                                placeholder="Enter Your Confirm Password" required>
                            <p id="confirm-password-message" style="color: red;"></p>

                            <button name="submit" class="btn" type="submit">Register</button>
                        </form>

                        <div class="social-icons">
                            <p>Have an account! <a href="index-one.php">Login</a>.</p>
                            <div style="display: flex;">
                                <p><a href="../My Market.php">Home Page</a></p>
                            </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.querySelector('input[name="password"]');
            const confirmPasswordInput = document.querySelector('input[name="confirm-password"]');
            const passwordMessage = document.getElementById('password-message');
            const confirmPasswordMessage = document.getElementById('confirm-password-message');

            passwordInput.addEventListener('input', function() {
                const password = passwordInput.value;
                let message = '';

                if (password.length < 8) {
                    message = 'Password must be at least 8 characters.';
                } else if (!/[A-Z]/.test(password)) {
                    message = 'Password must contain at least 1 uppercase letter.';
                } else if (!/[0-9]/.test(password)) {
                    message = 'Password must contain at least 1 number.';
                } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                    message = 'Password must contain at least 1 special character.';
                }

                if (message) {
                    passwordInput.style.borderColor = 'red';
                    passwordMessage.textContent = message;
                } else {
                    passwordInput.style.borderColor = 'green';
                    passwordMessage.textContent = '';
                }
            });

            confirmPasswordInput.addEventListener('input', function() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (password && confirmPassword) {
                    if (password !== confirmPassword) {
                        confirmPasswordInput.style.borderColor = 'red';
                        confirmPasswordMessage.textContent = 'Passwords do not match.';
                    } else {
                        confirmPasswordInput.style.borderColor = 'green';
                        confirmPasswordMessage.textContent = '';
                    }
                }
            });
        });
    </script>



</body>

</html>