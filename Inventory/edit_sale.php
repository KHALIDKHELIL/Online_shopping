<?php
  $page_title = 'Edit sale';
  require_once('includes/load.php');
  // Check what level user has permission to view this page
  page_require_level(3);
?>

<?php
// Find the payment record by the ID passed in the URL
$sale = find_by_id('payments', (int)$_GET['id']);
if (!$sale) {
    $session->msg("d", "Missing payment record.");
    redirect('sales.php');
}

// Find the product associated with the payment
$product = find_by_id('product', $sale['POrderId']);
if (!$product) {
    $session->msg("d", "Missing product associated with payment.");
    redirect('sales.php');
}

// Handle the update sale form submission
if (isset($_POST['update_sale'])) {
    $req_fields = array('title', 'quantity', 'price', 'total', 'date');
    validate_fields($req_fields);

    if (empty($errors)) {
        $p_id   = $db->escape((int)$product['Id']); // Product ID
        $s_qty  = $db->escape((int)$_POST['quantity']); // Quantity
        $s_total = $db->escape($_POST['total']); // Total
        $date   = $db->escape($_POST['date']); // Date
        $s_date = date("Y-m-d", strtotime($date)); // Format the date

        // Update the payments table with the new values
        $sql  = "UPDATE payments SET ";
        $sql .= "PUserid='{$p_id}', amount='{$s_total}', date='{$s_date}' ";
        $sql .= "WHERE PayId='{$sale['PayId']}'";

        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            // Update product stock
            update_product_qty($s_qty, $p_id);

            $session->msg('s', "Sale updated.");
            redirect('edit_sale.php?id=' . $sale['PayId'], false);
        } else {
            $session->msg('d', 'Sorry, failed to update!');
            redirect('sales.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_sale.php?id=' . (int)$sale['PayId'], false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
  <div class="panel">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Edit Sale</span>
     </strong>
     <div class="pull-right">
       <a href="sales.php" class="btn btn-primary">Show all sales</a>
     </div>
    </div>
    <div class="panel-body">
       <table class="table table-bordered">
         <thead>
           <th> Product Title </th>
           <th> Qty </th>
           <th> Price </th>
           <th> Total </th>
           <th> Date</th>
           <th> Action</th>
         </thead>
         <tbody id="product_info">
           <tr>
             <form method="post" action="edit_sale.php?id=<?php echo (int)$sale['PayId']; ?>">
               <td id="s_name">
                 <input type="text" class="form-control" id="sug_input" name="title" 
                        value="<?php echo remove_junk($product['Productname']); ?>">
                 <div id="result" class="list-group"></div>
               </td>
               <td id="s_qty">
                 <input type="text" class="form-control" name="quantity" 
                        value="<?php echo (int)$sale['PUserid']; ?>">
               </td>
               <td id="s_price">
                 <input type="text" class="form-control" name="price" 
                        value="<?php echo remove_junk($product['ProductDetail']); ?>">
               </td>
               <td>
                 <input type="text" class="form-control" name="total" 
                        value="<?php echo remove_junk($sale['amount']); ?>">
               </td>
               <td id="s_date">
                 <input type="date" class="form-control datepicker" name="date" data-date-format="" 
                        value="<?php echo remove_junk($sale['date']); ?>">
               </td>
               <td>
                 <button type="submit" name="update_sale" class="btn btn-primary">Update Sale</button>
               </td>
             </form>
           </tr>
         </tbody>
       </table>
    </div>
  </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
