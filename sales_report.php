<?php
ob_start();
session_start();

include "config.php";

$query = "SELECT * FROM customer";
$result = mysqli_query($con, $query);
if ($result) {
  while ($row = mysqli_fetch_object($result)) {
    $customers[] = $row;
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
    <title>Sales Register</title>
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
    <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css" />
    <link rel="stylesheet" href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css" />
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
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
                                <form method="post" name="myForm" action="sales_report.php" enctype="multipart/form-data">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="col-deep-purple m-0">Sales Register</h4>
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
                                                <label class="col-blue">Customer</label>
                                                <select name="customer" id="customer" required>
                                                    <option value="">Select Customer</option>
                                                    <option value="all">All Customers</option>
                                                    <?php foreach ($customers as $row) { ?>
                                                        <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
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
        <th>S.No</th>
        <th>Bill No</th>
        <th>Bill Date</th>
        <th>Bill Type</th>
        <th>Customer Name</th>
        <th>Customer Type</th>
        <th>Customer Mobile</th>
        <th>Preview</th>
        <th>Net Total</th>
        <th>Tax Total</th>
        <th>Grand Total</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $result1 = $con->query("SELECT a.sale_no,a.id, a.sale_date, a.total, b.name, b.type, b.phone,a.net_total, a.tax_total, a.tax_total,a.sale_type FROM sales a INNER JOIN customer b ON a.customer = b.id WHERE a.sale_date BETWEEN '$from_date' AND '$to_date' GROUP BY a.id");
      $sales = $result1->fetch_all(MYSQLI_ASSOC);
      ?>

      <?php foreach ($sales as $key => $sale): ?>
        <tr <?php if ($sale['sale_type'] == 'Credit') echo 'style="background-color: yellow;"'; ?>>
          <td><?php echo $key+1; ?></td>
          <td><?php echo $sale['sale_no']; ?></td>
          <td><?php echo $sale['sale_date']; ?></td>
          <td><?php echo $sale['sale_type'];
          if ($sale['sale_type'] == 'Credit') {
            echo '<br><a href="sales-edit.php?id='.$sale['id'].'" >Change to Paid</a>';
          }
          ?>
        </td>
          <td><?php echo $sale['name']; ?></td>
          <td><?php echo $sale['type']; ?></td>
          <td><?php echo $sale['phone']; ?></td>
          <td><a href="preview_sale.php?sale_id=<?php echo $sale['id']; ?>" class="btn btn-primary text-white"><i class="fa fa-eye"></i></a></td>
          <td><?php echo $sale['net_total']; ?></td>
      
          <td><?php echo $sale['tax_total']; ?></td>
          <td><?php echo $sale['total']; ?></td>
        </tr>
      <?php endforeach; ?>
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
      let customer = new TomSelect('#customer', {}); 
</script>

</body>

</html>