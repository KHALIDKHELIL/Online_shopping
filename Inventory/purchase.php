<?php
  $page_title = 'All Purchases';
  require_once('includes/load.php');
  // Check what level user has permission to view this page
  page_require_level(2);
  
  // Fetch all purchases from the database
  $purchases = find_all_purchases(); // Define this function to fetch purchase records
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-right">
          <a href="add_purchase.php" class="btn btn-primary">Add New Purchase</a>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th> Supplier </th>
              <th> Product </th>
              <th class="text-center"> Unit Price </th>
              <th class="text-center"> Quantity </th>
              <th class="text-center"> Total Price </th>
              <th class="text-center"> Status </th>
              <th class="text-center" style="width: 100px;"> Actions </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($purchases as $purchase): ?>
            <tr>
              <td class="text-center"><?php echo count_id(); ?></td>
              <td> <?php echo remove_junk($purchase['supplierName']); ?> </td>
              <td> <?php echo remove_junk($purchase['Productname']); ?> </td>
              <td class="text-center"> <?php echo remove_junk($purchase['unitPrice']); ?> </td>
              <td class="text-center"> <?php echo remove_junk($purchase['quantity']); ?> </td>
              <td class="text-center"> <?php echo remove_junk($purchase['totalPrice']); ?> </td>
              <td class="text-center"> <?php echo remove_junk($purchase['status']); ?> </td>
              <td class="text-center">
                <div class="btn-group">
                  <a href="edit_purchase.php?Id=<?php echo (int)$purchase['Id']; ?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>
                  <a href="delete_purchase.php?Id=<?php echo (int)$purchase['Id']; ?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-trash"></span>
                  </a>
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
