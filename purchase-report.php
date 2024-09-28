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
    <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css" />
    <link rel="stylesheet" href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css" />
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico" />
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
                            <form method="post" name="myForm" action="purchase-report.php" enctype="multipart/form-data">
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
                    <label class="col-blue">Supplier</label>
                    <select name="supplier" id="supplier" required>
                        <option value="">Select Supplier</option>
                        <option value="all">All Suppliers</option>
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

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];
            $supplier = $_POST['supplier'];
            $where = $_POST['supplier'] == 'all' ? '' : "and b.supplier_id = '$supplier'";
            
            $sql = "SELECT a.receipt_no, a.receipt_date, b.supplier_name, b.city, a.invoice_no, a.invoice_date, a.grand_total FROM purchase a INNER JOIN suppliers b ON a.purchase_id = b.supplier_id WHERE a.receipt_date BETWEEN '$from_date' AND '$to_date' $where";

            $result1 = $con->query($sql);
            $purchase = $result1->fetch_all(MYSQLI_ASSOC);
        ?>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Receipt No</th>
                                <th>Receipt Date</th>
                                <th>Supplier Name</th>
                                <th>City</th>
                                <th>Invoice No</th>
                                <th>Invoice Date</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($purchase as $key => $purchases) : ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $purchases['receipt_no']; ?></td>
                                    <td><?php echo $purchases['receipt_date']; ?></td>
                                    <td><?php echo $purchases['supplier_name']; ?></td>
                                    <td><?php echo $purchases['city']; ?></td>
                                    <td><?php echo $purchases['invoice_no']; ?></td>
                                    <td><?php echo $purchases['invoice_date']; ?></td>
                                    <td><?php echo $purchases['grand_total']; ?></td>
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

     let supplier = new TomSelect('#supplier', {});

     document.addEventListener('alpine:init', () => {
        Alpine.data('app', () => ({

            

        }))
    })
</script>

</body>

</html>