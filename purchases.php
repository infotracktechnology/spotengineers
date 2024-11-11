<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

include "config.php";

// Set default dates: start date is the first day of the current month, end date is today's date
$start_date = date('Y-m-01'); // First day of the current month
$end_date = date('Y-m-d'); // Today's date

// If start_date and end_date are passed via GET, use them
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
}

// Query to fetch purchases based on the selected or default date range
$purchases = $con->query("SELECT a.*, b.supplier_name, b.city 
                          FROM purchase a 
                          INNER JOIN suppliers b ON a.supplier = b.supplier_id
                          WHERE a.receipt_date BETWEEN '$start_date' AND '$end_date'
                          GROUP BY a.purchase_id");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Purchases</title>
    <link rel="stylesheet" href="assets/css/app.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/bundles/stepper/stepper.min.css">
    <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css">
    <link rel="stylesheet" href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php require('sidebar.php'); ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-10 mb-3">
                                                <h6 class="col-deep-purple">Purchases Details</h6>
                                            </div>
                                        </div>
                                        <!-- Date Filter Form -->
                                        <form action="purchases.php" class="row mb-3" method="get">
                                            <div class="col-md-3 col-sm-12">
                                                <input type="date" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : $start_date; ?>" class="form-control form-control-sm" />
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <input type="date" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : $end_date; ?>" class="form-control form-control-sm" />
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <button type="submit" class="btn btn-success">Search</button>
                                            </div>
                                        </form>

                                        <!-- Purchases Table -->
                                        <div class="table-responsive">
                                            <table class="table table-sm" id="myTable">
                                                <thead>
                                                    <tr role="row">
                                                        <th>S.No</th>
                                                        <th>Inward No</th>
                                                        <th>Inward Date</th>
                                                        <th>Invoice No</th>
                                                        <th>Invoice Date</th>
                                                        <th>Supplier</th>
                                                        <th>City</th>
                                                        <th>Return</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 1;
                                                    while ($row = $purchases->fetch_assoc()) {
                                                    ?>
                                                        <tr>
                                                            <td><?= $i ?></td>
                                                            <td><?= $row['receipt_no'] ?></td>
                                                            <td><?= $row['receipt_date'] ?></td>
                                                            <td><?= $row['invoice_no'] ?></td>
                                                            <td><?= $row['invoice_date'] ?></td>
                                                            <td><?= $row['supplier_name'] ?></td>
                                                            <td><?= $row['city'] ?></td>
                                                            <td><a href="purchase-return.php?id=<?= $row['purchase_id'] ?>" class="btn btn-success text-white"><i class="fa fa-plus"></i></a></td>
                                                        </tr>
                                                    <?php
                                                        $i++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
            </div>
        </div>
        <script src="assets/js/app.min.js"></script>
        <script src="assets/js/scripts.js"></script>
        <script src="assets/js/custom.js"></script>
        <script src="assets/bundles/datatables/datatables.min.js"></script>
        <script src="assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
        <script>
            const table = $('#myTable').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
            });
        </script>
</body>

</html>
