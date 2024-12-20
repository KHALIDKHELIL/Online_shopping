<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once('includes/load.php');

// Ensure database connection is established
$conn = new mysqli('localhost', 'root', '', 'online_shopping');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$page_title = 'Add Admin';
page_require_level(1);

// Fetch user groups for dynamic dropdown
$user_groups = [];
$stmt = $conn->prepare("SELECT id, group_name, group_level FROM user_groups WHERE group_status = 1");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $user_groups[] = $row;
}

function getNextId($conn)
{
    // Select all IDs and sort them
    $stmt = $conn->prepare("SELECT id FROM admin");
    $stmt->execute();
    $result = $stmt->get_result();

    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row['id'];
    }

    // Find the smallest available ID
    $nextId = 1;
    foreach ($ids as $id) {
        if ($id == $nextId) {
            $nextId++;
        } else {
            break;
        }
    }

    return $nextId;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $user_level = (int)$_POST['user_level'];
    $code = md5(rand());

    // Check for unique email
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $msg = "This email address has already been registered.";
    } else {
        // Check for unique username
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $msg = "This username is already taken.";
        } else {
            if ($password === $confirm_password) {
                $hashedPassword = sha1($password);
                $nextID = getNextId($conn);

                // Check if the selected user level exists in user_groups
                $stmt = $conn->prepare("SELECT group_level FROM user_groups WHERE group_level = ?");
                $stmt->bind_param("i", $user_level);
                $stmt->execute();
                $level_result = $stmt->get_result();

                if ($level_result->num_rows === 0) {
                    $msg = "The selected user level does not exist.";
                } else {
                    // Insert new admin
                    $stmt = $conn->prepare("INSERT INTO admin (id, name, email, username, password, user_level, verification_token, status) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
                    $stmt->bind_param("issssss", $nextID, $name, $email, $username, $hashedPassword, $user_level, $code);
                    $result = $stmt->execute();

                    if ($result) {
                        $mail = new PHPMailer(true);

                        try {
                            // Server settings
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'khalidkhelil19@gmail.com';  // Use your Gmail address
                            $mail->Password = 'umgunlsmnojktcck';  // Use your Gmail app password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;

                            // Recipients
                            $mail->setFrom('khalidkhelil19@gmail.com', 'Account Verification');
                            $mail->addAddress($email);

                            // Content
                            $mail->isHTML(true);
                            $mail->Subject = 'Verify your account';
                            $mail->Body = "To verify your Admin account, click the link: <a href='http://localhost:7880/Final_Project/Inventory/verify_admin.php?verification=" . $code . "'>Verify your email</a>";

                            // Send email
                            $mail->send();
                            $msg = "We have sent you a verification code to the email.";
                        } catch (Exception $e) {
                            $msg = "Error: Could not send verification email. Please try again later. Mailer Error: {$mail->ErrorInfo}";
                        }
                    } else {
                        $msg = "Error occurred during registration. Please try again.";
                    }
                }
            } else {
                $msg = "Passwords do not match.";
            }
        }
    }
}
?>

// Display Message
<?php if (!empty($msg)): ?>
    <div class='alert alert-danger'><?php echo $msg; ?></div>
<?php endif; ?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Add New Admin</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirm Password</label>
                        <input type="password" name="confirm-password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="user_level">User Level</label>
                        <select name="user_level" class="form-control" required>
                            <?php foreach ($user_groups as $group): ?>
                                <option value="<?php echo $group['group_level']; ?>">
                                    <?php echo $group['group_name']; ?> (Level <?php echo $group['group_level']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Add Admin</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
