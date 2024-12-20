<?php
$page_title = 'Purchase';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
// $all_categories = find_all('category');
$all_products= find_all('product');
$all_supplier = find_all('suppliers');
?>
<?php

// Include necessary files and start session


if (isset($_POST['add_purchase'])) {
    // Required fields
    $req_fields = ['product-supplier', 'theproduct', 'unitPrice', 'quantity', 'totalPrice','confirmation'];
    validate_fields($req_fields);

    if (empty($errors)) {
        // Sanitize and escape user inputs
        $supplier = remove_junk($db->escape($_POST['product-supplier']));
        $product = remove_junk($db->escape($_POST['theproduct']));
        $unit_price = remove_junk($db->escape($_POST['unitPrice']));
        $quantity = remove_junk($db->escape($_POST['quantity']));
        $total_price = remove_junk($db->escape($_POST['totalPrice']));
        $status = remove_junk($db->escape($_POST['confirmation']));
        // $date = make_date();

        // Insert into the database
        $query = "INSERT INTO purchase (";
        $query .= "itemId, supplierId, quantity, unitPrice, totalPrice,status";
        $query .= ") VALUES (";
        $query .= " '{$product}', '{$supplier}', '{$quantity}', '{$unit_price}', '{$total_price}','{$status}'";
        $query .= ")";

        // Execute the query
        if ($db->query($query)) {
            $session->msg('s', "Purchase added successfully.");
            redirect('add_purchase.php', false);
        } else {
            $session->msg('d', 'Failed to add the purchase.');
            redirect('add_purchase.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_purchase.php', false);
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
          <form method="post" action="add_purchase.php" class="clearfix">
            <!-- <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-th-large"></i>
                </span>
                <input type="text" class="form-control" name="product-title" placeholder="Product Title">
              </div>
            </div> -->
            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                  <select class="form-control" name="product-supplier">
                    <option value="">Select Item Supplier</option>
                    <?php foreach ($all_supplier as $supp): ?>
                      <option value="<?php echo (int)$supp['Id'] ?>">
                        <?php echo $supp['supplierName'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <select class="form-control" name="theproduct">
                    <option value="">Select an Item </option>
                    <?php foreach ($all_products as $product): ?>
                      <option value="<?php echo (int)$product['Id'] ?>">
                        <?php echo $product['Productname'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              
              </div>
            </div>
            <!-- Detail and product photo -->
            
            <!-- Quantity and buy n sell price -->

            <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                    <i class="glyphicon glyphicon-usd">ETB</i>
                    </span>
                    <input type="number" class="form-control" id="unitPrice" name="unitPrice" step="0.01" onchange="calculateTotal()" required>

                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                    <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="number" class="form-control" id="quantity" name="quantity" onchange="calculateTotal()" required>
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="text" class="form-control" id="totalPrice" name="totalPrice" readonly>
                   
                    <!-- <span class="input-group-addon">.00</span> -->
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" class="form-control" id="totalPrice" name="confirmation" value="Unconfirmed">
            <button type="submit" name="add_purchase" class="btn btn-danger">Purchase</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>