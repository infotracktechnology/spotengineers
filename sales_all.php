<?php
ob_start();
session_start();
include "config.php";

// Set default dates: start date is the first day of the current month, end date is today's date
$start_date = date('Y-m-01'); // First day of the current month
$end_date = date('Y-m-d'); // Today's date

// If start_date and end_date are passed via GET, use them
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
}

// Query to fetch sales data based on the selected or default date range
$sql = "SELECT a.sale_no, a.id, a.sale_date, a.total, b.name, b.type, b.phone, a.net_total, a.tax_total, a.tax_total, a.sale_type 
        FROM sales a 
        INNER JOIN customer b ON a.customer = b.id 
        WHERE a.sale_date BETWEEN '$start_date' AND '$end_date' 
        GROUP BY a.id";

// Execute the query
$result1 = $con->query($sql);
$sales = $result1->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Sales Return</title>
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
                                <form method="get" name="myForm" action="sales_all.php" enctype="multipart/form-data">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="col-deep-purple m-0">Sales Return</h4>
                                        </div>
                                        <div class="card-body">
                                            <!-- Date Filter -->
                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <label for="start_date">Start Date</label>
                                                    <input type="date" name="start_date" id="start_date" value="<?php echo $start_date; ?>" class="form-control" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="end_date">End Date</label>
                                                    <input type="date" name="end_date" id="end_date" value="<?php echo $end_date; ?>" class="form-control" />
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-success mt-4">Filter</button>
                                                </div>
                                            </div>

                                            <!-- Sales Table -->
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No</th>
                                                            <th>Bill No</th>
                                                            <th>Bill Date</th>
                                                            <th>Customer Name</th>
                                                            <th>Bill Type</th>
                                                            <th>Customer Mobile</th>
                                                            <th>Net Total</th>
                                                            <th>Tax Total</th>
                                                            <th>Grand Total</th>
                                                            <th>Return</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        if (count($sales) > 0) {
                                                            foreach ($sales as $key => $sale): ?>
                                                                <tr <?php if ($sale['sale_type'] == 'Credit') echo 'style="background-color: yellow;"'; ?>>
                                                                    <td><?php echo $key+1; ?></td>
                                                                    <td><?php echo $sale['sale_no']; ?></td>
                                                                    <td><?php echo $sale['sale_date']; ?></td>
                                                                    <td><?php echo $sale['name']; ?></td>
                                                                    <td><?php echo $sale['sale_type']; ?></td>
                                                                    <td><?php echo $sale['phone']; ?></td>
                                                                    <td><?php echo $sale['net_total']; ?></td>
                                                                    <td><?php echo $sale['tax_total']; ?></td>
                                                                    <td><?php echo $sale['total']; ?></td>
                                                                    <td><a href="sales_return.php?id=<?php echo $sale['id']; ?>" class="btn btn-success text-white"><i class="fa fa-edit"></i></a></td>
                                                                </tr>
                                                            <?php endforeach;
                                                        } else { ?>
                                                            <tr><td colspan="10" class="text-center">No records found for the selected date range.</td></tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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
    <!-- Template JS File -->
    <script src="assets/js/scripts.js"></script>
    <!-- Custom JS File -->
    <script src="assets/js/custom.js"></script>
</body>

</html>
