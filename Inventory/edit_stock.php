<?php

$page_title = 'Edit Stock';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
// page_require_level(2);
?>
<?php


// Fetch the purchase details using the provided ID
$stock = find_by_id('stock', (int)$_GET['Id']);
$all_products = find_all('product');

// If no stock entry is found, redirect to the stock page
if (!$stock) {
    $session->msg("d", "No stock entry found for the provided ID.");
    redirect('stock.php');
}

// Retrieve the `itemId` from the stock
$stock_item_id = $stock['itemId'];

// Check if the form was submitted
if (isset($_POST['edit_stock'])) {
    $req_fields = array('product-id', 'quantity', 'unitPrice', 'sellPrice', 'confirmdate');
    validate_fields($req_fields); // Ensure all required fields are present

    if (empty($errors)) {
        // Sanitize and assign input values
        $p_product_id = (int)remove_junk($db->escape($_POST['product-id']));
        $p_quantity = (int)remove_junk($db->escape($_POST['quantity']));
        $p_unit_price = remove_junk($db->escape($_POST['unitPrice']));
        $p_sell_price = remove_junk($db->escape($_POST['sellPrice']));
        $c_date = remove_junk($db->escape($_POST['confirmdate']));

        // Check if the product already exists in the stock table
        $stock_query = "SELECT * FROM stock WHERE itemId='{$p_product_id}' LIMIT 1";
        $result_stock_check = $db->query($stock_query);
        $existing_stock = $db->fetch_assoc($result_stock_check);

        if ($existing_stock) {
            // Product exists in stock, update its details
           // $new_quantity = $existing_stock['quantity'] + $p_quantity;
            $query_stock = "UPDATE stock SET 
                quantity='{$p_quantity}', 
                buyPrice='{$p_unit_price}', 
                sellPrice='{$p_sell_price}', 
                dateCreated='{$c_date}' 
                WHERE itemId='{$p_product_id}'";
        // } else {
        //     // Product does not exist in stock, insert a new entry
        //     $query_stock = "INSERT INTO stock (itemId, quantity, buyPrice, sellPrice, dateCreated) 
        //         VALUES ('{$p_product_id}', '{$p_quantity}', '{$p_unit_price}', '{$p_sell_price}', '{$c_date}')";
        }

        // Execute the stock query
        $result_stock = $db->query($query_stock);

        if ($result_stock) {
            // Stock update successful
            $session->msg('s', "Stock updated successfully.");
            redirect('stock.php', false);
        } else {
            // Failed to update stock
            $session->msg('d', 'Failed to update stock.');
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
       ?>
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
                <form method="post" action="edit_stock.php?Id=<?php echo (int)$stock['Id'] ?>">
                    <div class="form-group">
                        <label for="product-id">Product</label>
                        <select class="form-control" name="product-id" >
                            <option value="">Select a product</option>
                            <?php foreach ($all_products as $product): ?>
                                <option value="<?php echo (int)$product['Id']; ?>" <?php if ($stock['itemId'] == $product['Id']) echo "selected"; ?> >
                                    <?php echo remove_junk($product['Productname']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                   
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" name="quantity" value="<?php echo remove_junk($stock['quantity']); ?>"  >
                    </div>
                    <div class="form-group">
                        <label for="unitPrice">Unit Price</label>
                        <input type="number" step="0.01" class="form-control" name="unitPrice" value="<?php echo remove_junk($stock['buyPrice']); ?>" >
                    </div>
                    <div class="form-group">
                        <label for="sellPrice">Selling Price</label>
                        <input type="number" step="0.01" class="form-control" name="sellPrice" value="<?php echo remove_junk($stock['sellPrice']); ?>" >
                        <input type="hidden" class="form-control" name="confirmdate" value="<?php echo date('y-m-d');?>">
                    </div>
                    <button type="submit" name="edit_stock" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
