<?php
session_start();
?>

<?php
// Connect to the database using PDO
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "market_database";

// Use try-catch block to handle errors
try {
    // Create a PDO instance with the connection parameters
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve form data using filter_input function
    $cardNumber = filter_input(INPUT_POST, 'cardNumber', FILTER_SANITIZE_STRING);
    $expiryDate = filter_input(INPUT_POST, 'expiryDate', FILTER_SANITIZE_STRING);

    // Check if the card number already exists in the database using a SELECT query
    $stmt = $conn->prepare("SELECT * FROM card_info WHERE card_id = :cardNumber");
    $stmt->bindParam(':cardNumber', $cardNumber);
    $stmt->execute();

    // If the query returns any row, then the card number is duplicated
    if ($stmt->rowCount() > 0) {
        // Display a user-friendly message
        echo "Sorry, this card number is already registered. Please use another card or contact our support team.";
    } else {
        // Otherwise, proceed with the insertion of the new card number and expiry date
        $stmt = $conn->prepare("INSERT INTO card_info (card_id, expiry) VALUES (:cardNumber, :expiryDate)");
        $stmt->bindParam(':cardNumber', $cardNumber);
        $stmt->bindParam(':expiryDate', $expiryDate);
        $stmt->execute();

        // Check if the insertion was successful and redirect accordingly
        if ($stmt->rowCount() > 0) {
            // Redirect to the success page
            header("Location: address.php");
            exit();
        } else {
            // Redirect to an error page
            header("Location: error.php");
            exit();
        }
    }
} catch (PDOException $e) {
    // Display the error message
    echo "Connection failed: " . $e->getMessage();
}

// Close the database connection
$conn = null;
