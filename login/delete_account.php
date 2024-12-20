<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: http://localhost:7880/Final_project/login/index-one.php");
    exit();
}

$message = "";
$user_id = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

        // Clear session and redirect
        session_destroy();
        $message = "Your account has been successfully deleted.";
        header("Location: http://localhost:7880/Final_project/login/index-one.php?message=" . urlencode($message));
        exit();
    } else {
        $message = "Incorrect password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
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
            background: #ff5252;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #d32f2f;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Delete Account</h2>
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="password">Confirm Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Delete Account</button>
        </form>
    </div>
</body>

</html>
