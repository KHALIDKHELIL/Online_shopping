<?php
  $page_title = 'All Stock';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  // page_require_level(2);
  
  $stock = find_all('stock');
  $all_products = find_all('product');
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
           <a href="unconfirm_items.php" class="btn btn-primary">Add New Stock</a>
         </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <!-- <th class="text-center" style="width: 50px;">#</th> -->
                <th> Item ID </th>
                <th>Item Name</th>
                <th class="text-center"> Quantity </th>
                <th class="text-center"> Buy Price </th>
                <th class="text-center"> Sell Price </th>
                <th class="text-center"> Date Created </th>
                <th class="text-center" style="width: 100px;"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($all_products as $product):
              foreach ($stock as $item ):
                
                    
                    if ($item['itemId'] == $product['Id']){ ?>
              <tr>
                
                <!-- <td class="text-center"><?php echo count_id(); ?></td> -->
                <td> <?php echo remove_junk($item['itemId']); ?> </td>
               
                <td> <?php echo remove_junk($product['Productname']);
                 ?></td>
                <td class="text-center"> <?php echo remove_junk($item['quantity']); ?> </td>
                <td class="text-center"> <?php echo remove_junk($item['buyPrice']); ?> </td>
                <td class="text-center"> <?php echo remove_junk($item['sellPrice']); ?> </td>
                <td class="text-center"> <?php echo read_date($item['dateCreated']); ?> </td>
                <td class="text-center">
                
                  <div class="btn-group">
                    <a href="edit_stock.php?Id=<?php echo (int)$item['Id']; ?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_stock.php?Id=<?php echo (int)$item['Id']; ?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
              <?php };  
              endforeach;
              endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>
