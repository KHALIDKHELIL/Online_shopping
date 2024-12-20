<?php
$page_title = 'Status Check';
require_once('includes/load.php');

// Check user session
if (!$session->isUserLoggedIn(true)) {
    redirect('../login/index-one.php', false);
}

// Fetch logged-in admin details
$current_admin_id = (int)$_SESSION['user_id'];
$current_admin = find_by_id('admin', $current_admin_id);

if (!$current_admin) {
    $session->msg("d", "Admin not found.");
    redirect('logout.php');
}

// Restrict access if admin is deactivated
if ((int)$current_admin['status'] === 0) {
    $session->msg("d", "Your account is deactivated. Please contact the administrator.");
    redirect('logout.php');
}

// Redirect to home.php if admin is active
if ((int)$current_admin['status'] === 1) {
    header("Location: ../inventory/home.php");
    exit();
}

// Include the header for authorized users
include_once('layouts/header.php');
?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-12">
        <h2>Welcome, <?php echo remove_junk(ucwords($current_admin['name'])); ?>!</h2>
        <p>Your account is active. You can continue using the system.</p>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>