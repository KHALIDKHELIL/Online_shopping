<?php
  $page_title = 'Edit categorie';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  //Display all catgories.
  $suppliers = find_by_id('suppliers', (int)$_GET['Id']);
  if(!$suppliers){
    $session->msg("d","Missing categorie id.");
    redirect('Supplier.php');
  }
?>

<?php
if(isset($_POST['edit_supp'])){
  $req_field = array('supp_name','supp_contact','supp_status','supp_address');
  validate_fields($req_field);
  $supp_name = remove_junk($db->escape($_POST['supp_name']));
  $supp_contact = remove_junk($db->escape($_POST['supp_contact']));
  $supp_status = remove_junk($db->escape($_POST['supp_status']));
  $supp_address = remove_junk($db->escape($_POST['supp_address']));
  if(empty($errors)){
        $sql = "UPDATE suppliers SET supplierName='{$supp_name}',supplierContact='{$supp_contact}',status='{$supp_status}',supplierAddress='{$supp_address}'";
       $sql .= " WHERE Id='{$suppliers['Id']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Categorie");
       redirect('Supplier.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
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
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editing <?php echo remove_junk(ucfirst($suppliers['supplierName']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_supplier.php?Id=<?php echo (int)$suppliers['Id'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="supp_name" value="<?php echo remove_junk(ucfirst($suppliers['supplierName']));?>">
           </div>
           <div class="form-group">
               <input type="text" class="form-control" name="supp_contact" value="<?php echo remove_junk(ucfirst($suppliers['supplierContact']));?>">
           </div>
           <div class="form-group">
               <input type="text" class="form-control" name="supp_status" value="<?php echo remove_junk(ucfirst($suppliers['status']));?>">
           </div>
           <div class="form-group">
               <input type="text" class="form-control" name="supp_address" value="<?php echo remove_junk(ucfirst($suppliers['supplierAddress']));?>">
           </div>
           <button type="submit" name="edit_supp" class="btn btn-primary">Update categorie</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
