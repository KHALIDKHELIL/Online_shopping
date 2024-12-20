<?php
session_start();
include 'config.php';

$msg = "";

if (isset($_GET['verification'])) {
    $verification_code = $_GET['verification'];

    if (isset($_SESSION['verification_completed']) && $_SESSION['verification_completed'] === true) {
        $msg = "Verification already completed. You cannot verify your account again.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM customer WHERE userCode=? AND verified=0");
        $stmt->bind_param("s", $verification_code);
        $stmt->execute();
        $query = $stmt->get_result();

        if ($query->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE customer SET verified=1 WHERE userCode=?");
            $stmt->bind_param("s", $verification_code);
            $update_query = $stmt->execute();

            if ($update_query) {
                $_SESSION['verification_completed'] = true;
                $msg = "Your account has been successfully verified!";
            } else {
                $msg = "Error verifying your account. Please try again later.";
            }
        } else {
            $msg = "Invalid verification code or account already verified.";
        }
    }
} else {
    $msg = "Verification code not found.";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification</title>
    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Styles for the message box */
        .message-box {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            background-color: #f2f2f2;
            text-align: center;
        }

        .message {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #00c16e;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #4ca356;
        }
        .btn.home {
            background-color: blue;
        }
        .btn.product {
            background-color: #4ca356;
        }
        .btn.log {
            background-color: red;
        }
    </style>
</head>

<body>
    <div class="message-box">
        <p class="message"><?php echo $msg; ?></p>
        <a href="../My Market.php" class="btn home">Go to Homepage</a>
        <a href="../ProductPage.php" class="btn product">View Products</a>
        <a href="index-one.php" class="btn log">Log In</a>
    </div>
</body>

</html>
