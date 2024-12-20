<?PHP
// Your database connection parameters
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


// Step 2: Implementation
use Chapa\Chapa;
use Chapa\PostData;
use Chapa\Util;

// Fetch data from the order table (Replace with your actual query)
$orderId = 123; // Replace with the actual order ID
$stmt = $pdo->prepare("SELECT * FROM order_table WHERE id = :id");
$stmt->bindParam(':id', $orderId);
$stmt->execute();
$orderData = $stmt->fetch(PDO::FETCH_ASSOC);



// Instantiate Chapa class with your secret key
$chapa = new Chapa('{CHASECK_TEST-UNGRw6XsjBTx0MLtRDuDQnhSivN2BbRb}');


// Generate a transaction reference token
$transactionRef = Util::generateToken('acme'); // Replace 'acme' with your prefix if needed

// Fetch data from the order table (Replace with your actual query)
$orderId = 123; // Replace with the actual order ID
$stmt = $pdo->prepare("SELECT * FROM order_table WHERE id = :id");
$stmt->bindParam(':id', $orderId);
$stmt->execute();
$orderData = $stmt->fetch(PDO::FETCH_ASSOC);

// Use the fetched data to create payment details in Chapa
if ($orderData) {
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

    // Now you can proceed to use $postData with Chapa for payment initialization
    // For example:
    $response = $chapa->initialize($postData);

    // Handle the Chapa response as needed
    echo $response->getMessage() . PHP_EOL;
    echo $response->getStatus() . PHP_EOL;
    echo print_r($response->getData(), true) . PHP_EOL;
    echo $response->getRawJson() . PHP_EOL;
} else {
    echo "Order not found or no data available.";
}
<input type="hidden" name="public_key" value="CHAPUBK_TEST-sK2APDMiQ38gD539Ig15jvi8GyoS0rXl" />
<!-- <input type="hidden" name="tx_ref" value="<?php $username + $currentDate ?>" /> -->
<input type="hidden" name="tx_ref" value="negade-txt" />
<!-- <input type="hidden" name="amount" value="<?php $price_total ?>" /> -->
<input type="hidden" name="amount" value="2000" />
<input type="hidden" name="currency" value="ETB" />
<!-- <input type="hidden" name="email" value="<?php $userEmail ?>" /> -->
<input type="hidden" name="email" value="jordisavage90@gmail.com" />
<!-- <input type="hidden" name="first_name" value="<?php $username ?>" /> -->
<input type="hidden" name="first_name" value="Yordi" />
<!-- <input type="hidden" name="last_name" value="<?php $Lusername ?>" /> -->
<input type="hidden" name="last_name" value="teferi" />
<input type="hidden" name="title" value="Let us do this" />
<input type="hidden" name="description" value="Paying with Confidence with cha" />
<input type="hidden" name="logo" value="https://chapa.link/asset/images/chapa_swirl.svg" />
<input type="hidden" name="callback_url" value="http://localhost:7880/Final_Project/checkout.php" />
<input type="hidden" name="return_url" value="http://localhost:7880/Final_Project/mymarket.php" />
<input type="hidden" name="meta[title]" value="test" />
