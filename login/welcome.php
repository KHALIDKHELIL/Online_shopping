<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: ../login/index-one.php");
    exit();
}

include 'config.php';

$query = mysqli_query($conn, "SELECT * FROM customer WHERE userEmail='{$_SESSION['email']}'");

if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                background-color: #f2f2f2;
            }

            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                text-align: center;
            }

            h1 {
                color: #333;
            }

            .btn {
                display: inline-block;
                padding: 10px 20px;
                margin: 10px;
                background-color: #007bff;
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
                transition: background-color 0.3s;
            }

            .btn:hover {
                background-color: #0056b3;
            }
            .btn.log{
                background-color: red;
            }
            .btn.product{
                background-color: green;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h1>Welcome <?php echo htmlspecialchars($row['userFname']); ?></h1>
            <a href="../ProductPage.php" class="btn product">Proceed to Product Page</a>
            <a href="../My Market.php" class="btn">Go Back to Homepage</a>
            <a href="logout.php" class="btn log">Logout</a>
        </div>
    </body>

    </html>

<?php
}
?>