<?php
ob_start();
session_start();
include "config.php";
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}



$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01');
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : '';

// Fetch customers for the dropdown
$customers = $con->query("SELECT id, name FROM customer")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Bill Register | Spot Engineers</title>
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
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css" />
    <link rel="stylesheet" href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="assets/bundles/datatables/export-tables/buttons.dataTables.min.css" />
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
                                
                                <form method="get" name="filterForm" action="">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="col-deep-purple m-0">Bill Register</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">From Date</label>
                                                    <input type="date" name="from_date" class="form-control form-control-sm" value="<?php echo $from_date; ?>" />
                                                </div>

                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">To Date</label>
                                                    <input type="date" name="to_date" class="form-control form-control-sm" value="<?php echo $to_date; ?>" />
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label class="col-blue">Customer</label>
                                                    <select name="customer_id" class="form-control form-control-sm">
                                                        <option value="">Select Customer</option>
                                                        <option value="all" <?= ($customer_id == 'all') ? 'selected' : ''; ?>>All Customers</option>
                                                        <?php foreach ($customers as $customer) { ?>
                                                            <option value="<?php echo $customer['id']; ?>" <?= ($customer_id == $customer['id']) ? 'selected' : ''; ?>><?php echo $customer['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <?php if ($customer_id) { ?>
                                <div class="col-md-12">
                                    <table class="table table-sm" id="myTable">
                                        <thead>
                                            <tr role="row">
                                                <th>S.No</th>
                                                <th>Bill No</th>
                                                <th>Bill Date</th>
                                                <th>Category</th>
                                                <th>Customer</th>
                                                <th>Customer Phone</th>
                                                <th>GST No</th>
                                                <th>Net Total</th>
                                                <th>Tax Total</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT a.*, b.name, b.phone, b.gst_no FROM `bills` a INNER JOIN customer b ON a.customer = b.id WHERE";

                                            if ($from_date && $to_date) {
                                                $query .= "a.bill_date BETWEEN '$from_date' AND '$to_date'";
                                            }

                                            if ($customer_id && $customer_id != 'all') {
                                                $query .= " AND a.customer = '$customer_id'";
                                            }

                                            $bills = $con->query($query)->fetch_all(MYSQLI_ASSOC);
                                            foreach ($bills as $i => $row) { ?>
                                                <tr>
                                                    <td><?php echo $i + 1; ?></td>
                                                    <td><?php echo $row['bill_no']; ?></td>
                                                    <td><?php echo $row['bill_date']; ?></td>
                                                    <td><?php if (is_null($row['job_no'])) {
                                                            echo "Sale";
                                                        } else {
                                                            echo "Job";
                                                        } ?></td>
                                                    <td><?php echo $row['name']; ?></td>
                                                    <td><?php echo $row['phone']; ?></td>
                                                    <td><?php echo $row['gst_no']; ?></td>
                                                    <td><?php echo $row['net_total']; ?></td>
                                                    <td><?php echo $row['tax_total']; ?></td>
                                                    <td><?php echo $row['total']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                </section>
            </div>
        </div>
    </div>
    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="assets/bundles/datatables/datatables.min.js"></script>
    <script src="assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/bundles/datatables/export-tables/dataTables.buttons.min.js"></script>
    <script src="assets/bundles/datatables/export-tables/buttons.flash.min.js"></script>
    <script src="assets/bundles/datatables/export-tables/jszip.min.js"></script>
    <script src="assets/bundles/datatables/export-tables/pdfmake.min.js"></script>
    <script src="assets/bundles/datatables/export-tables/vfs_fonts.js"></script>
    <script src="assets/bundles/datatables/export-tables/buttons.html5.min.js"></script>
    <script src="assets/bundles/datatables/export-tables/buttons.print.min.js"></script>
    <script>
        const table = $('#myTable').DataTable({
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    </script>

</body>

</html>