<?php
  $page_title = 'Add Suppliers';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
 page_require_level(1);
  
  $all_suppliers = find_all('suppliers')
?>
<?php
 if(isset($_POST['add_cat'])){
   $req_field = array('supplierName','supplierContact','status','supplierAddress');
   validate_fields($req_field);
   $supp_name = remove_junk($db->escape($_POST['supplierName']));
   $supp_contact = remove_junk($db->escape($_POST['supplierContact']));
   $supp_status = remove_junk($db->escape($_POST['status']));
   $supp_address = remove_junk($db->escape($_POST['supplierAddress']));
   if(empty($errors)){
      $sql  = "INSERT INTO suppliers (supplierName,supplierContact,status,supplierAddress)";
      $sql .= " VALUES ('{$supp_name}','{$supp_contact}','{$supp_status}','{$supp_address}')";
      if($db->query($sql)){
        $session->msg("s", "Successfully Added New Suppliers");
        redirect('Supplier.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('Supplier.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('Supplier.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>

  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>
   <div class="row">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Supplier</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="Supplier.php">
            <div class="form-group">
                <input type="text" class="form-control" name="supplierName" placeholder="Supplier Name">
            </div>
            <div class="form-group">
                <input type="tel" class="form-control" name="supplierContact" placeholder="Supplier contact">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="status" placeholder="Supplier status">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="supplierAddress" placeholder="Supplier address">
            </div>
            <button type="submit" name="add_cat" class="btn btn-primary">Add Supplier</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Suppliers</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Supplier Name</th>
                    <th>Supplier Contact</th>
                    <th>Supplier Status</th>
                    <th>Supplier Address</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_suppliers as $supp):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($supp['supplierName'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($supp['supplierContact'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($supp['status'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($supp['supplierAddress'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_supplier.php?Id=<?php echo (int)$supp['Id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_supplier.php?Id=<?php echo (int)$supp['Id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
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
  </div>
  <?php include_once('layouts/footer.php'); ?>
