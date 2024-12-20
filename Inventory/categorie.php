<?php
  $page_title = 'All Categories and Subcategories';
  require_once('includes/load.php');

  // Checkin What level user has permission to view this page
  page_require_level(2);

  // Fetch categories and subcategories
  $all_categories = find_all('category');
  $all_subcategories = find_all('subcatgory');
?>

<?php
// Add new category
if (isset($_POST['add_cat'])) {
    $req_field = array('categorie-name');
    validate_fields($req_field);
    $cat_name = remove_junk($db->escape($_POST['categorie-name']));
    if (empty($errors)) {
        $sql = "INSERT INTO category (categoryname) VALUES ('{$cat_name}')";
        if ($db->query($sql)) {
            $session->msg("s", "Successfully Added New Category");
            redirect('categorie.php', false);
        } else {
            $session->msg("d", "Sorry Failed to insert.");
            redirect('categorie.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('categorie.php', false);
    }
}

// Add new subcategory
if (isset($_POST['add_subcat'])) {
    $req_field = array('subcategory-name', 'category-id');
    validate_fields($req_field);
    $subcat_name = remove_junk($db->escape($_POST['subcategory-name']));
    $category_id = (int)$_POST['category-id'];
    if (empty($errors)) {
        $sql = "INSERT INTO subcatgory (Subcategoryname, categoryid) VALUES ('{$subcat_name}', '{$category_id}')";
        if ($db->query($sql)) {
            $session->msg("s", "Successfully Added New Subcategory");
            redirect('categorie.php', false);
        } else {
            $session->msg("d", "Sorry Failed to insert.");
            redirect('categorie.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('categorie.php', false);
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
    <!-- Add Category -->
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Add New Category</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="categorie.php">
                    <div class="form-group">
                        <input type="text" class="form-control" name="categorie-name" placeholder="Category Name">
                    </div>
                    <button type="submit" name="add_cat" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>

        <!-- Add Subcategory -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Add New Subcategory</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="categorie.php">
                    <div class="form-group">
                        <input type="text" class="form-control" name="subcategory-name" placeholder="Subcategory Name">
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="category-id">
                            <option value="">Select Category</option>
                            <?php foreach ($all_categories as $cat): ?>
                                <option value="<?php echo (int)$cat['Id']; ?>">
                                    <?php echo remove_junk(ucfirst($cat['categoryname'])); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="add_subcat" class="btn btn-primary">Add Subcategory</button>
                </form>
            </div>
        </div>
    </div>

    <!-- List Categories and Subcategories -->
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>All Categories</span>
                </strong>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Category</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                            <th>Subcategories</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php foreach ($all_categories as $cat): ?>
        <tr>
            <td class="text-center"><?php echo count_id(); ?></td>
            <td><?php echo remove_junk(ucfirst($cat['categoryname'])); ?></td>
            <td class="text-center">
                <div class="btn-group">
                    <a href="edit_categorie.php?Id=<?php echo (int)$cat['Id']; ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                        <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_categorie.php?Id=<?php echo (int)$cat['Id']; ?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>
                </div>
            </td>
            <td>
                <ul>
                    <?php foreach ($all_subcategories as $subcat): ?>
                        <?php if ($subcat['categoryid'] == $cat['Id']): ?>
                            <li>
                                <?php echo remove_junk(ucfirst($subcat['Subcategoryname'])); ?>
                                <div class="btn-group" style="padding: 20px;">
                                    <a href="edit_subcatgorie.php?Id=<?php echo (int)$subcat['Id']; ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                    <a href="delete_subcategorie.php?Id=<?php echo (int)$subcat['Id']; ?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
