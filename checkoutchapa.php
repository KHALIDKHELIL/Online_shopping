<?php
// Your database connection parameters

use Chapa\Model\PostData;
use Chapa\Util;

$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_username';
$dbPass = 'your_password';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch data from the order table dynamically
// Replace this logic with your specific way of obtaining order details, maybe using $_GET, $_POST, or any other method
// For example, assuming you receive an order ID from somewhere:
if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id']; // Replace with the actual method of obtaining the order ID

    // Fetch order details from the database based on the obtained order ID
    $stmt = $pdo->prepare("SELECT * FROM order_table WHERE id = :id");
    $stmt->bindParam(':id', $orderId);
    $stmt->execute();
    $orderData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Use the fetched data to create payment details in Chapa
    if ($orderData) {
        // Initialize Chapa
        $chapa = new Chapa('{your-secret-key}');

        // Generate transaction reference
        $transactionRef = Util::generateToken('acme');

        // Create PostData object for payment details using order data
        $postData = new PostData();
        $postData->amount($orderData['total_amount'])
            ->currency($orderData['currency'])
            ->email($orderData['customer_email'])
            ->firstname($orderData['customer_firstname'])
            ->lastname($orderData['customer_lastname'])
            ->transactionRef($transactionRef)
            ->callbackUrl('https://chapa.co')
            ->customizations([
                'customization[title]' => 'E-commerce',
                'customization[description]' => 'It is time to pay'
            ]);

        // Use $postData with Chapa for payment initialization
        $response = $chapa->initialize($postData);

        // Handle the Chapa response as needed
        echo $response->getMessage() . PHP_EOL;
        echo $response->getStatus() . PHP_EOL;
        echo print_r($response->getData(), true) . PHP_EOL;
        echo $response->getRawJson() . PHP_EOL;
    } else {
        echo "Order not found or no data available.";
    }
} else {
    echo "No order ID received.";
}
