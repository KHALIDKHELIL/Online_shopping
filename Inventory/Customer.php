<?php
  $page_title = 'Customer Management';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);

  // Fetch all customers
  $customers = find_all('customer'); // Assuming find_all fetches all rows from the customer table

  // Handle block/unblock action
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['block_user'])) {
      $userid = intval($_POST['block_user']);

      // Fetch customer by ID
      $customer = find_by_id('customer', $userid); // Assuming find_by_id fetches a single record by ID
      if ($customer) {
          // Toggle customerstatus
          $new_status = $customer['customerstatus'] == 0 ? 1 : 0;

          // Update status in the database
          $query_update = "UPDATE customer SET customerstatus = '{$new_status}' WHERE userid = '{$userid}'";
          if ($db->query($query_update)) {
              $session->msg('s', "User has been " . ($new_status ? "unblocked" : "blocked") . " successfully.");
          } else {
              $session->msg('d', "Failed to update user status.");
          }
      } else {
          $session->msg('d', "User not found.");
      }
      redirect('Customer.php', false);
  }
?>

<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <!-- <div class="panel-heading clearfix">
          <div class="pull-right">
            <a href="add_customer.php" class="btn btn-primary">Add New Customer</a>
          </div>
        </div> -->
        <div class="panel-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th class="text-center" style="width: 15%;">Status</th>
                <th class="text-center" style="width: 100px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($customers as $customer): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td> <?php echo remove_junk($customer['userFname']); ?></td>
                <td> <?php echo remove_junk($customer['userLname']); ?></td>
                <td> <?php echo remove_junk($customer['userEmail']); ?></td>
                <td class="text-center"> <?php echo $customer['customerstatus'] ? 'Unblocked' : 'Blocked'; ?></td>
                <td class="text-center">
                  <div class="btn-group">
                      <form method="POST" style="display:inline;">
                        <button type="submit" name="block_user" value="<?php echo (int)$customer['userid']; ?>" 
                                class="btn btn-<?php echo $customer['customerstatus'] ? 'danger' : 'success'; ?> btn-xs" 
                                title="<?php echo $customer['customerstatus'] ? 'Block' : 'Unblock'; ?>" data-toggle="tooltip">
                          <?php echo $customer['customerstatus'] ? 'Block' : 'Unblock'; ?>
                        </button>
                      </form>
                  </div>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>
