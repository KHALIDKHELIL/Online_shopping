<?php
$page_title = 'Add Product';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
 page_require_level(2);
$all_categories = find_all('category');
$all_Subcategories= find_all('subcatgory');
$all_photo = find_all('media');
?>
<?php
if (isset($_POST['add_product'])) {
  $req_fields = array('product-title','Productdetail','product-Subcategorie', 'product-categorie', 'admin-id');
  validate_fields($req_fields);
  if (empty($errors)) {
    $p_name  = remove_junk($db->escape($_POST['product-title']));
    $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
    $p_subcat = remove_junk($db->escape($_POST['product-Subcategorie']));
    $p_detail = remove_junk($db->escape($_POST['Productdetail']));
    // $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
    // $p_buy   = remove_junk($db->escape($_POST['buying-price']));
    // $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
    $p_adminid  = remove_junk($db->escape($_POST['admin-id']));

    // if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
    //   $media_id = '0';
    // } else {
      $p_photo = remove_junk($db->escape($_POST['product-photo']));

    $date    = make_date();
    $query  = "INSERT INTO product (";
    $query .= " ProductCatID,Prodsubcatid,Productname,productImage,ProductDetail,AdminID";
    $query .= ") VALUES (";
    $query .= " '{$p_cat}', '{$p_subcat}', '{$p_name}', '{$p_photo}', '{$p_detail}', '{$p_adminid}'";
    $query .= ")";
    $query .= " ON DUPLICATE KEY UPDATE Productname='{$p_name}'";
    if ($db->query($query)) {
      $session->msg('s', "Product added ");
      redirect('add_product.php', false);
    } else {
      $session->msg('d', ' Sorry failed to added!');
      redirect('product.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('add_product.php', false);
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
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Add New Product</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-th-large"></i>
                </span>
                <input type="text" class="form-control" name="product-title" placeholder="Product Title">
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                  <select class="form-control" name="product-categorie">
                    <option value="">Select Product Category</option>
                    <?php foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['Id'] ?>">
                        <?php echo $cat['categoryname'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <select class="form-control" name="product-Subcategorie">
                    <option value="">Select Product Sub Category</option>
                    <?php foreach ($all_Subcategories as $subcat): ?>
                      <option value="<?php echo (int)$subcat['Id'] ?>">
                        <?php echo $subcat['Subcategoryname'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              
              </div>
            </div>
            <!-- Detail and product photo -->
            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                <input type="text" placeholder="enter product Detail" name="Productdetail" class="form-control">
                </div>
              
                <div class="col-md-6">
                  <!-- Choose product photo -->
                  <div class="">
                  <input type="file" accept="image/png, image/jpeg, image/jpg" name="product-photo" class="box">
                    
                  </div>
                  <!-- <select class="form-control" name="product-photo">
                      <option value="">Select Product Photo</option>
                    <?php foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>">
                        <?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                    </select> -->
                </div>
              </div>
            </div>
            <!-- Quantity and buy n sell price -->

            <!-- <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="number" class="form-control" name="buying-price" placeholder="Buying Price">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="number" class="form-control" name="saleing-price" placeholder="Selling Price">
                    <input type="hidden" name="admin-id" value="1">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
            </div> -->
            <input type="hidden" name="admin-id" value="1">
            <button type="submit" name="add_product" class="btn btn-danger">Add product</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>