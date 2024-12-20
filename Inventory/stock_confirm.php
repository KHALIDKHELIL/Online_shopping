<?php

$page_title = 'Confirm Purchase';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
 page_require_level(2);
?>
<?php


// Fetch the purchase details using the provided ID
$purchase = find_by_id('purchase', (int)$_GET['Id']);
$all_products = find_all('product');

// If no unconfirmed purchase is found, redirect to the stock confirmation page
if (!$purchase) {
    $session->msg("d", "There are no unconfirmed purchases found.");
    redirect('stock_confirm.php');
}

// Retrieve the `itemId` from the purchase
$purchase_item_id = $purchase['itemId'];

// Check if the form was submitted
if (isset($_POST['confirm_purchase'])) {
    $req_fields = array('product-id', 'quantity', 'unitPrice', 'sellPrice', 'confirmdate');
    validate_fields($req_fields); // Ensure all required fields are present

    if (empty($errors)) {
        // Sanitize and assign input values
        $p_product_id = (int)remove_junk($db->escape($_POST['product-id']));
        $p_quantity = (int)remove_junk($db->escape($_POST['quantity']));
        $p_unit_price = remove_junk($db->escape($_POST['unitPrice']));
        $p_sell_price = remove_junk($db->escape($_POST['sellPrice']));
        $c_date = remove_junk($db->escape($_POST['confirmdate']));

        // Update the purchase status to 'confirmed'
        $query_purchase = "UPDATE purchase SET status='confirmed' WHERE Id='{$purchase['Id']}'";
        $result_purchase = $db->query($query_purchase);

        if ($result_purchase && $db->affected_rows() === 1) {
             // Check if the product already exists in the stock
             $stock_query = "SELECT * FROM stock WHERE itemId='{$purchase_item_id}' LIMIT 1";
             $result_stock_check = $db->query($stock_query);
             $stock = $db->fetch_assoc($result_stock_check);
            if ($stock) {
                // Product exists, update stock details
                $new_quantity = $stock['quantity'] + $p_quantity;
                $query_stock = "UPDATE stock SET 
                    quantity='{$new_quantity}', 
                    buyPrice='{$p_unit_price}', 
                    sellPrice='{$p_sell_price}', 
                    dateCreated='{$c_date}' 
                    WHERE itemId='{$purchase_item_id}'";
            } else {
                // Product does not exist, insert a new stock entry
                $query_stock = "INSERT INTO stock (itemId, quantity, buyPrice, sellPrice, dateCreated) 
                    VALUES ('{$purchase_item_id}', '{$p_quantity}', '{$p_unit_price}', '{$p_sell_price}', '{$c_date}')";
            }

            // Execute the stock query
            $result_stock = $db->query($query_stock);

            if ($result_stock) {
                // Stock update successful
                $session->msg('s', "Purchase confirmed and stock updated successfully.");
                redirect('unconfirm_items.php', false);
            } else {
                // Failed to update stock
                $session->msg('d', 'Failed to update stock.');
            }
        } else {
            // Failed to confirm purchase
            $session->msg('d', 'Failed to confirm purchase.');
        }
    } else {
        // Display validation errors
        $session->msg("d", $errors);
    }
}

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); 
        echo "item ID".$purchase_item_id;?>
    </div>
</div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Confirm Purchase</span>
            </strong>
        </div>
        <div class="panel-body">
            <div class="col-md-7">
                <form method="post" action="stock_confirm.php?Id=<?php echo (int)$purchase['Id'] ?>">
                    <div class="form-group">
                        <label for="product-id">Product</label>
                        <select class="form-control" name="product-id" readonly>
                            <option value="">Select a product</option>
                            <?php foreach ($all_products as $product): ?>
                                <option value="<?php echo (int)$product['Id']; ?>" <?php if ($purchase['itemId'] == $product['Id']) echo "selected"; ?> readonly>
                                    <?php echo remove_junk($product['Productname']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                   
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" name="quantity" value="<?php echo remove_junk($purchase['quantity']); ?>" readonly >
                    </div>
                    <div class="form-group">
                        <label for="unitPrice">Unit Price</label>
                        <input type="number" step="0.01" class="form-control" name="unitPrice" value="<?php echo remove_junk($purchase['unitPrice']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="sellPrice">Selling Price</label>
                        <input type="number" step="0.01" class="form-control" name="sellPrice" required>
                        <input type="hidden" class="form-control" name="confirmdate" value="<?php echo date('y-m-d');?>">
                    </div>
                    <button type="submit" name="confirm_purchase" class="btn btn-primary">Confirm Purchase</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
