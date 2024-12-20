<?php
$page_title = 'Edit product';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
 page_require_level(2);
?>
<?php
$product = find_by_id('product', (int)$_GET['Id']);
$all_categories = find_all('category');
$all_Subcategories = find_all('subcatgory');
// $all_photo = find_all('media');
if (!$product) {
  $session->msg("d", "Missing product id.");
  redirect('product.php');
}
?>
<?php
if (isset($_POST['product'])) {
  $req_fields = array('product-title', 'Productdetail', 'product-Subcategorie', 'product-categorie','admin-id');
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
    //}
    $query   = "UPDATE product SET";
    $query  .= " ProductCatID ='{$p_cat}',Prodsubcatid ='{$p_subcat}',Productname ='{$p_name}',productImage ='{$p_photo}',ProductDetail ='{$p_detail}',";
    $query  .= " AdminID ='{$p_adminid}'";
    $query  .= " WHERE Id ='{$product['Id']}'";
    $result = $db->query($query);
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Product updated ");
      redirect('product.php', false);
    } else {
      $session->msg('d', ' Sorry failed to updated!');
      redirect('edit_product.php?Id=' . $product['Id'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_product.php?Id=' . $product['Id'], false);
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
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Add New Product</span>
      </strong>
    </div>
    <div class="panel-body">
      <div class="col-md-7">
        <form method="post" action="edit_product.php?Id=<?php echo (int)$product['Id'] ?>">
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-th-large"></i>
              </span>
              <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['Productname']); ?>">
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" name="product-categorie">
                  <option value=""> Select a categorie</option>
                  <?php foreach ($all_categories as $cat): ?>
                    <option value="<?php echo (int)$cat['Id']; ?>" <?php if ($product['ProductCatID'] === $cat['Id']): echo "selected";
                                                                    endif; ?>>
                      <?php echo remove_junk($cat['categoryname']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control" name="product-Subcategorie">
                  <option value=""> Select a Sub categorie</option>
                  <?php foreach ($all_Subcategories as $subcat): ?>
                    <option value="<?php echo (int)$subcat['Id']; ?>" <?php if ($product['Prodsubcatid'] === $subcat['Id']): echo "selected";
                                                                      endif; ?>>
                      <?php echo remove_junk($subcat['Subcategoryname']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>


            </div>
          </div>
          <div class="form-group">

            <div class="row">

              <div class="col-md-6">
                <input type="text" placeholder="enter product Detail" name="Productdetail" class="form-control" value="<?php echo remove_junk($product['ProductDetail']); ?>">
              </div>
              <div class="col-md-6">
                <div class="form-control">
                  <input type="file" accept="image/png, image/jpeg, image/jpg" src="../image/" name="product-photo" value="<?php echo $product['productImage']; ?>" >

                </div>
                <!-- <select class="form-control" name="product-photo">
                      <option value=""> No image</option>
                      <?php foreach ($all_photo as $photo): ?>
                        <option value="<?php echo (int)$cat['Id']; ?>" <?php if ($product['productImage'] === $cat['Id']): echo "selected";
                                                                        endif; ?> >
                          <?php echo $photo['file_name'] ?></option>
                      <?php endforeach; ?>
                    </select> -->
              </div>
            </div>
          </div>

          <!-- <div class="form-group">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="qty">Quantity</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="number" class="form-control" name="product-quantity" value="<?php echo remove_junk($product['inStock']); ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="qty">Buying price</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="hidden" name="admin-id" value="1">
                    <input type="number" class="form-control" name="buying-price" value="<?php echo remove_junk($product['buyPrice']); ?>">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="qty">Selling price</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="number" class="form-control" name="saleing-price" value="<?php echo remove_junk($product['sellPrice']); ?>">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
          <input type="hidden" name="admin-id" value="1">
          <button type="submit" name="product" class="btn btn-danger">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>