<?php
ob_start();
session_start();
include "config.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['sale_id']) || empty($_GET['sale_id'])) {
    die("Sale ID not provided.");
}

$sale_id = $_GET['sale_id'];

// Fetch sale details using sale_id
$sale = $con->query("SELECT * FROM `sales` a INNER JOIN customer b ON a.customer = b.id WHERE a.id = '$sale_id'")->fetch_object();

// Fetch sale items
$sale_items = $con->query("SELECT a.*, b.name, b.hsn, b.brand FROM sales_items a INNER JOIN items b ON a.item_id = b.item_id WHERE a.sale_id = '$sale_id'")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Spot Engineers | Preview</title>
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
                                            <div class="col-md-10 col-sm-12 mb-3">
                                                <h6 class="col-deep-purple">Preview</h6>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm" id="myTable">
                                                <tbody>
                                                    <tr>
                                                        <th>Sale No</th>
                                                        <td><?php echo $sale->sale_no; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Customer Name</th>
                                                        <td><?php echo $sale->name; ?></td>
                                                    </tr>
                                                   
                                                    <tr>
                                                        <th>Sale Date</th>
                                                        <td><?php echo $sale->sale_date; ?></td>
                                                        
                                                    <tr>
                                                        <th>Address</th>
                                                        <td><?php echo $sale->address_line_1 . "<br>" . $sale->address_line_2; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Phone No</th>
                                                        <td><?php echo $sale->phone; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>GST No</th>
                                                        <td><?php echo $sale->gst_no; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <hr style="border-bottom: 1.5px solid #000;">
                                            <h6 class="col-deep-purple">Item Details</h6>
                                            <table class="table table-bordered table-sm" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th>SI No.</th>
                                                        <th>Goods Description</th>
                                                        <th>HSN/SAC</th>
                                                        <th>Brand</th>
                                                        <th>Qty</th>
                                                        <th>Rate</th>
                                                        <th>Discount</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $grand_total = 0;
                                                    foreach ($sale_items as $i => $item) {
                                                        $grand_total += $item['amount'];
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $i + 1; ?></td>
                                                            <td><?php echo $item['name']; ?></td>
                                                            <td><?php echo $item['hsn']; ?></td>
                                                            <td><?php echo $item['brand']; ?></td>
                                                            <td><?php echo $item['qty']; ?></td>
                                                            <td><?php echo $item['rate']; ?></td>
                                                            <td><?php echo $item['discount']; ?></td>
                                                            <td><?php echo $item['amount']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <th colspan="7" style="text-align:right">Grand Total</th>
                                                        <th><?php echo $grand_total; ?></th>
                                                    </tr>
                                                </tbody>
                                            </table><br>
                                          
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