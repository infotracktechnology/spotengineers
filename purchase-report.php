<?php
ob_start();
session_start();

include "config.php";

$query = "SELECT * FROM suppliers";
$result = mysqli_query($con, $query);
if ($result) {
    while ($row = mysqli_fetch_object($result)) {
        $suppliers[] = $row;
    }
}


$currentDate = date('Y-m-d');

$fromDate = date('Y-m-01');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Purchase Register</title>
   <!-- General CSS Files -->
   <link rel="stylesheet" href="assets/css/app.min.css" />
    <link rel="stylesheet" href="assets/bundles/summernote/summernote-bs4.css" />
    <link rel="stylesheet" href="assets/bundles/jquery-selectric/selectric.css" />
    <link rel="stylesheet" href="assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" />
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/components.css" />
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="assets/css/custom.css" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body>
    <div class="loader"></div>
    <div id="app" ng-app="myApp" ng-controller="issueController">
        <div class="main-wrapper main-wrapper-1">
            <?php require('sidebar.php'); ?>
            <!-- Main Content -->
            <div class="main-content" x-data="app">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form method="post" name="myForm" action="purchase_report.php" enctype="multipart/form-data">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="col-deep-purple m-0">Purchase Register</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                               
                                            <div class="col-md-2 form-group">
                                                <label class="col-blue">From Date</label>
                                                <input type="date" name="from_date" class="form-control form-control-sm" value="<?php echo $fromDate; ?>" />
                                            </div>

                                            <div class="col-md-2 form-group">
                                                <label class="col-blue">To Date</label>
                                                <input type="date" name="to_date" class="form-control form-control-sm" value="<?php echo $currentDate; ?>" />
                                            </div>

                                            <div class="col-md-4 form-group">
                                                    <label class="col-blue">Supplier </label>
                                                    <select name="supplier" id="supplier"  required>
                                                        <option value="">Select Supplier</option>
                                                        <?php foreach ($suppliers as $row) { ?>
                                                            <option value="<?php echo $row->supplier_id; ?>"><?php echo $row->supplier_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                        
                                            <div class="col-md-2 form-group">
                                                <button type="submit" class="btn btn-success" style="margin-top: 25px;">Submit</button>
                                            </div>
                                        </div>

                                    </div>
                                  
                        <?php if($_SERVER["REQUEST_METHOD"] == "POST"){ 
                          
                          $from_date = $_POST['from_date'];
                          $to_date = $_POST['to_date'];
                          
                          ?>

                                    <div class="card-body">
                                    <div class="table-responsive">
  <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
    <thead>
      <tr>
       
      </tr>
    </thead>
    <tbody>
    
    </tbody>
  </table>
</div>

                  </div>

                  <?php } ?>

                            </div>
                            </form>
                        </div>
                    </div>
            </div>
            </section>
        </div>
    </div>
    </div>
    <!-- General JS Scripts -->
    <script src="assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <!-- Page Specific JS File -->
  <script src="assets/bundles/datatables/datatables.min.js"></script>
  <script src="assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/bundles/datatables/export-tables/dataTables.buttons.min.js"></script>
  <script src="assets/bundles/datatables/export-tables/buttons.flash.min.js"></script>
  <script src="assets/bundles/datatables/export-tables/jszip.min.js"></script>
  <script src="assets/bundles/datatables/export-tables/pdfmake.min.js"></script>
  <script src="assets/bundles/datatables/export-tables/vfs_fonts.js"></script>
  <script src="assets/bundles/datatables/export-tables/buttons.print.min.js"></script>
  <script src="assets/js/page/datatables.js"></script>
  <script src="assets/bundles/select2/dist/js/select2.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>


  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  
  <script src="assets/js/custom.js"></script>

<script>

     let supplier = new TomSelect('#supplier', {});

     document.addEventListener('alpine:init', () => {
        Alpine.data('app', () => ({

            

        }))
    })
</script>

</body>

</html>