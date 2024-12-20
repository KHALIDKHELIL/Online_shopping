<?php
require_once 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify token
    $query = "SELECT userid, pendingEmail FROM customer WHERE emailToken = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['userid'];
        $new_email = $row['pendingEmail'];

        // Update email
        $update_query = "UPDATE customer SET userEmail = ?, pendingEmail = NULL, emailToken = NULL WHERE userid = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $new_email, $user_id);
        $update_stmt->execute();

        echo "Email updated successfully.";
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>
