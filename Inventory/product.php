<?php
  $page_title = 'All Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
 page_require_level(2);
  $products = join_product_table();
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
           <a href="add_product.php" class="btn btn-primary">Add New</a>
         </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Photo</th>
                <th> Product Title </th>
                <th class="text-center" style="width: 10%;"> Categories </th>
                <th class="text-center" style="width: 10%;">Sub Categories </th>
                 <th class="text-center" style="width: 40%;"> Description </th>
                <!-- <th class="text-center" style="width: 10%;"> Description </th>
                <th class="text-center" style="width: 10%;"> Buying Price </th>
                <th class="text-center" style="width: 10%;"> Selling Price </th> -->
                <!-- <th class="text-center" style="width: 10%;"> Product Added </th> -->
                <th class="text-center" style="width: 100px;"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td>
                  <?php //if($product['media_id'] === '0'): ?>
                    <!-- <img class="img-avatar img-circle" src="../image/iphone14.jpg" alt=""> -->
                  <?php //else: ?>
                  <img class="img-avatar img-circle" src="..//image/<?php echo $product['productImage']; ?>" alt="">
                <?php // endif; ?>
                </td>
                <td> <?php echo remove_junk($product['Productname']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['categoryname']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['Subcategoryname']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['ProductDetail']); ?></td>
                <!-- <td class="text-center"> <?php echo remove_junk($product['inStock']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['buyPrice']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['sellPrice']); ?></td> -->
                <!-- <td class="text-center"> <?php echo read_date($product['date']); ?></td> -->
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_product.php?Id=<?php echo (int)$product['Id'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_product.php?Id=<?php echo (int)$product['Id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
