<?php
$page_title = 'Sale Report';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-6">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="text-center" style="color: #2c3e50;">Generate Sales Report</h3>
            </div>
            <div class="panel-body">
                <form class="clearfix" method="post" action="sale_report_process.php">
                    <div class="form-group">
                        <label class="form-label" style="color: #34495e; font-weight: bold;">Date Range</label>
                        <div class="input-group">
                            <input type="text" class="datepicker form-control" name="start-date" placeholder="From" style="border-color: #1abc9c;">
                            <span class="input-group-addon" style="background-color: #16a085; color: white;"><i class="glyphicon glyphicon-menu-right"></i></span>
                            <input type="text" class="datepicker form-control" name="end-date" placeholder="To" style="border-color: #1abc9c;">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-success btn-block" style="background-color: #27ae60; border-color: #27ae60;">
                            Generate Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
   
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
