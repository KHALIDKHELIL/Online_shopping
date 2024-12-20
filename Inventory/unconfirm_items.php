<?php
$page_title = 'Unconfirmed Items';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
// page_require_level(2);

// Fetch all unconfirmed items from the purchase table
$unconfirmed_items = find_by_value('purchase', 'Unconfirmed', 'status');
if (!$unconfirmed_items) {
  $session->msg("d", "There is No unconfirmed purchases found.");
  // redirect('unconfirm_items.php');
}
// Ensure $unconfirmed_items is an array
if (!is_array($unconfirmed_items)) {
  $unconfirmed_items = [];
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Unconfirmed Items</span>
        </strong>

      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th> Item ID </th>
              <th class="text-center"> Quantity </th>
              <th class="text-center"> Unit Price </th>
              <th class="text-center"> Status </th>
              <th class="text-center" style="width: 100px;"> Actions </th>
            </tr>
          </thead>
          <tbody>
            <?php //if (!empty($unconfirmed_items)): 
            ?>
            <?php foreach ($unconfirmed_items as $item): ?>
              <tr>
                <td class="text-center"> <?php echo count_id(); ?> </td>
                <td> <?php echo remove_junk($item['itemId']); ?> </td>
                <td class="text-center"> <?php echo remove_junk($item['quantity']); ?> </td>
                <td class="text-center"> <?php echo remove_junk($item['unitPrice']); ?> </td>
                <td class="text-center"> <?php echo remove_junk($item['status']); ?> </td>
                <td class="text-center">
                  <div class="btn-group">
                   <button class="btn btn-info btn-xs"> <a href="stock_confirm.php?Id=<?php echo (int)$item['Id']; ?>" class="btn btn-info btn-xs" title="Confirm" data-toggle="tooltip">
                      <!-- <span class="btn btn-primary"> -->
                        Confirm
                      <!-- </span> -->
                    </a></button>
                    <!-- <a href="delete_product.php?Id=<?php echo (int)$item['Id']; ?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a> -->
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php //else: 
            ?>
            <!-- <tr>
                <td colspan="6" class="text-center">No unconfirmed items found.</td>
              </tr> -->
            <?php //endif; 
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>