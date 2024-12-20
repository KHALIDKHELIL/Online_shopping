<?php
  $page_title = 'Edit Subcategory';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);

  // Find the subcategory by ID
  $subcategory = find_by_id('subcatgory', (int)$_GET['Id']);
  if (!$subcategory) {
      $session->msg("d", "Missing subcategory id.");
      redirect('categorie.php');
  }

  // Fetch all categories for the dropdown
  $all_categories = find_all('category');
?>

<?php
if (isset($_POST['edit_subcat'])) {
    $req_field = array('subcategory-name', 'category-id');
    validate_fields($req_field);
    $subcat_name = remove_junk($db->escape($_POST['subcategory-name']));
    $category_id = (int)$_POST['category-id'];

    if (empty($errors)) {
        $sql = "UPDATE subcatgory SET Subcategoryname='{$subcat_name}', categoryid='{$category_id}' ";
        $sql .= " WHERE Id='{$subcategory['Id']}'";
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg("s", "Successfully updated Subcategory");
            redirect('categorie.php', false);
        } else {
            $session->msg("d", "Sorry! Failed to Update");
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
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Editing <?php echo remove_junk(ucfirst($subcategory['Subcategoryname'])); ?></span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="edit_subcategorie.php?Id=<?php echo (int)$subcategory['Id']; ?>">
                    <div class="form-group">
                        <input type="text" class="form-control" name="subcategory-name" value="<?php echo remove_junk(ucfirst($subcategory['Subcategoryname'])); ?>">
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="category-id">
                            <option value="">Select Category</option>
                            <?php foreach ($all_categories as $cat): ?>
                                <option value="<?php echo (int)$cat['Id']; ?>" <?php if ($cat['Id'] == $subcategory['categoryid']) echo 'selected'; ?>>
                                    <?php echo remove_junk(ucfirst($cat['categoryname'])); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="edit_subcat" class="btn btn-primary">Update Subcategory</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
