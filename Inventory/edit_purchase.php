<?php
$page_title = 'Edit Purchase';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
 page_require_level(2);
?>
<?php
$purchase = find_by_id('purchase', (int)$_GET['Id']);
$all_suppliers = find_all('suppliers');
$all_products = find_all('product');

if (!$purchase) {
  $session->msg("d", "Missing purchase id.");
  redirect('purchase.php');
}
?>
<?php
if (isset($_POST['edit_purchase'])) {
  $req_fields = array('product-id', 'supplier-id', 'quantity', 'unitPrice', 'totalPrice');
  validate_fields($req_fields);

  if (empty($errors)) {
    $p_product_id   = remove_junk($db->escape($_POST['product-id']));
    $p_supplier_id  = remove_junk($db->escape($_POST['supplier-id']));
    $p_quantity     = remove_junk($db->escape($_POST['quantity']));
    $p_unit_price   = remove_junk($db->escape($_POST['unitPrice']));
    $p_total_price  = remove_junk($db->escape($_POST['totalPrice']));
    // $p_purchase_date = remove_junk($db->escape($_POST['purchase-date']));

    $query   = "UPDATE purchase SET";
    $query  .= " itemId='{$p_product_id}', supplierId='{$p_supplier_id}', quantity='{$p_quantity}',";
    $query  .= " unitPrice='{$p_unit_price}', totalPrice='{$p_total_price}'";
    $query  .= " WHERE Id='{$purchase['Id']}'";
    $result = $db->query($query);

    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Purchase updated successfully.");
      redirect('purchase.php', false);
    } else {
      $session->msg('d', 'Failed to update purchase.');
      redirect('edit_purchase.php?Id=' . $purchase['Id'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_purchase.php?Id=' . $purchase['Id'], false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<script>
       function calculateTotal() {
    // Get the unit price and quantity values
    let unitPrice = parseFloat(document.getElementById('unitPrice').value) || 0;
    let quantity = parseFloat(document.getElementById('quantity').value) || 0;

    // Calculate total price
    let totalPrice = unitPrice * quantity;

    // Set the total price input value
    document.getElementById('totalPrice').value = totalPrice.toFixed(2);
}

    </script>
<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Edit Purchase</span>
      </strong>
    </div>
    <div class="panel-body">
      <div class="col-md-7">
        <form method="post" action="edit_purchase.php?Id=<?php echo (int)$purchase['Id'] ?>">
          <div class="form-group">
          <div class="row">
          <div class="col-md-6">
            <label for="supplier-id">Supplier</label>
            <select class="form-control" name="supplier-id">
              <option value="">Select a supplier</option>
              <?php foreach ($all_suppliers as $supplier): ?>
                <option value="<?php echo (int)$supplier['Id']; ?>" <?php if ($purchase['supplierId'] == $supplier['Id']) echo "selected"; ?>>
                  <?php echo remove_junk($supplier['supplierName']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label for="product-id">Product</label>
            <select class="form-control" name="product-id">
              <option value="">Select a product</option>
              <?php foreach ($all_products as $product): ?>
                <option value="<?php echo (int)$product['Id']; ?>" <?php if ($purchase['itemId'] == $product['Id']) echo "selected"; ?>>
                  <?php echo remove_junk($product['Productname']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          </div>
         
          
          </div>
          <!-- <div class="form-group">
          <div class="row">
          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" class="form-control" name="quantity" value="<?php echo remove_junk($purchase['quantity']); ?>" required>
          </div>
          <div class="form-group">
            <label for="unit-price">Unit Price</label>
            <input type="number" step="0.01" class="form-control" id="unitPrice" name="unitPrice" step="0.01" onchange="calculateTotal()" value="<?php echo remove_junk($purchase['unitPrice']); ?>" required>
          </div>
          <div class="form-group">
            <label for="total-price">Total Price</label>
            <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" onchange="calculateTotal()" value="<?php echo remove_junk($purchase['totalPrice']); ?>" readonly>
          </div>
          </div>
          </div> -->

          <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="number" class="form-control" id="unitPrice" name="unitPrice" step="0.01" onchange="calculateTotal()" value="<?php echo remove_junk($purchase['unitPrice']); ?>" required>

                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="number" class="form-control" id="quantity" name="quantity" onchange="calculateTotal()" value="<?php echo remove_junk($purchase['quantity']); ?>" required>
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="text" class="form-control" id="totalPrice" name="totalPrice" value="<?php echo remove_junk($purchase['totalPrice']); ?>" readonly>
                   
                    <!-- <span class="input-group-addon">.00</span> -->
                  </div>
                </div>
              </div>
            </div>
          <!-- <div class="form-group">
            <label for="purchase-date">Purchase Date</label>
            <input type="date" class="form-control" name="purchase-date" value="<?php echo remove_junk($purchase['purchase_date']); ?>" required>
          </div> -->
          <button type="submit" name="edit_purchase" class="btn btn-primary">Update Purchase</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
