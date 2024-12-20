<?php
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(1);

// Validate the ID from GET parameter
$user_id = (int)$_GET['id'];
$target_user = find_by_id('admin', $user_id);

// Check if the target user exists
if (!$target_user) {
  $session->msg("d", "User not found.");
  redirect('users.php');
}

// Restrict deletion for admins in the same group
if ($target_user['user_level'] === $_SESSION['user_level'] && $target_user['id'] !== $_SESSION['user_id']) {
  $session->msg("d", "You cannot delete another admin in the same group.");
  redirect('users.php');
}

// Proceed with deletion
$delete_id = delete_by_id('admin', $user_id);
if ($delete_id) {
  $session->msg("s", "User deleted.");
  redirect('users.php');
} else {
  $session->msg("d", "User deletion failed.");
  redirect('users.php');
}
?>
