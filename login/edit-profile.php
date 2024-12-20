<?php
require_once 'config.php';
session_start();
require 'vendor/autoload.php'; // Include PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['userid'])) {
    header("Location: http://localhost:7880/Final_project/login/index-one.php");
    exit();
}

$message = "";
$user_id = $_SESSION['userid'];

// Fetch user details
$query = "SELECT userFname, userLname, userEmail FROM customer WHERE userid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_account'])) {
        $password = $_POST['password'];

        // Verify the password
        $password_query = "SELECT userPassword FROM customer WHERE userid = ?";
        $password_stmt = $conn->prepare($password_query);
        $password_stmt->bind_param("i", $user_id);
        $password_stmt->execute();
        $password_result = $password_stmt->get_result();
        $stored_password = $password_result->fetch_assoc()['userPassword'];

        if (password_verify($password, $stored_password)) {
            // Delete user account
            $delete_query = "DELETE FROM customer WHERE userid = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("i", $user_id);
            $delete_stmt->execute();

            // Redirect or display success message
            session_destroy(); // Clear session
            header("Location: http://localhost:7880/Final_project/login/index-one.php");
            exit();
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $userFname = trim($_POST['userFname']);
        $userLname = trim($_POST['userLname']);
        $userEmail = trim($_POST['userEmail']);

        if ($user && $user['userEmail'] !== $userEmail) {
            // Check if the new email already exists in the database
            $email_check_query = "SELECT userid FROM customer WHERE userEmail = ?";
            $email_check_stmt = $conn->prepare($email_check_query);
            $email_check_stmt->bind_param("s", $userEmail);
            $email_check_stmt->execute();
            $email_check_result = $email_check_stmt->get_result();

            if ($email_check_result->num_rows > 0) {
                $message = "The email address is already registered. Please use a different email.";
            } else {
                // Generate confirmation token
                $token = bin2hex(random_bytes(32));
                $confirm_link = "http://localhost:7880/Final_project/login/confirm_email.php?token=$token";

                // Save new email and token in the database
                $update_query = "UPDATE customer SET pendingEmail = ?, emailToken = ? WHERE userid = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("ssi", $userEmail, $token, $user_id);
                $update_stmt->execute();

                // Send confirmation email using PHPMailer
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'khalidkhelil19@gmail.com';
                    $mail->Password = 'umgunlsmnojktcck';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('khalidkhelil19@gmail.com', 'Your Project Name');
                    $mail->addAddress($userEmail);
                    $mail->isHTML(true);
                    $mail->Subject = 'Confirm Your New Email Address';
                    $mail->Body = "Click the link to confirm your new email: <a href='$confirm_link'>$confirm_link</a>";

                    $mail->send();
                    $message = "A confirmation link has been sent to your new email. Please verify to complete the update.";
                } catch (Exception $e) {
                    $message = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        } else {
            // Update only the name fields
            $update_query = "UPDATE customer SET userFname = ?, userLname = ? WHERE userid = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ssi", $userFname, $userLname, $user_id);
            $update_stmt->execute();
            $message = "Profile updated successfully.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .message {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            color: #555;
        }

        input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #0056b3;
        }

        .delete-account {
            background: #ff5252;
            color: white;
        }

        .delete-account:hover {
            background: #d32f2f;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="userFname">First Name:</label>
            <input type="text" id="userFname" name="userFname" value="<?= $user ? htmlspecialchars($user['userFname']) : '' ?>" required>

            <label for="userLname">Last Name:</label>
            <input type="text" id="userLname" name="userLname" value="<?= $user ? htmlspecialchars($user['userLname']) : '' ?>" required>

            <label for="userEmail">Email:</label>
            <input type="email" id="userEmail" name="userEmail" value="<?= $user ? htmlspecialchars($user['userEmail']) : '' ?>" required>

            <label for="password">Confirm Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Save Changes</button>
        </form>
        <form method="POST" action="delete_account.php">
            <h3>Delete Account</h3>
            <label for="delete_password">Confirm Password:</label>
            <input type="password" id="delete_password" name="password" required>
            <button type="submit" name="delete_account" class="delete-account">Delete Account</button>
        </form>
    </div>
</body>

</html>
