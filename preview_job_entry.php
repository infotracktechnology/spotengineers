<?php
ob_start();
session_start();
include "config.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Get job_id from URL
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
} else {
    die("Job ID not provided.");
}

// Fetch job details using job_id
$job = $con->query("SELECT * FROM `job_entry` WHERE `id` = '$job_id'")->fetch_object();

$customer = $con->query("SELECT * FROM `customer` WHERE `id` = '$job->customer_id'")->fetch_object();
$employee = $con->query("SELECT * FROM `employee` WHERE `id` = '$job->emp_id'")->fetch_object();

// Fetch spare issue items
$spare_issue_item = $con->query("
    SELECT a.*, b.name AS Name, b.brand AS Brand
    FROM spare_issue_item a
    INNER JOIN items b ON a.spare_id = b.item_id
    WHERE a.job_id = '$job_id'
");
$spare_item_count = $spare_issue_item->num_rows; // Count the number of spare items

// Fetch labour entries
$labour_entry = $con->query("
    SELECT a.*, b.title 
    FROM labour_entry a
    INNER JOIN work b ON a.work_id = b.id
    WHERE a.job_id = '$job_id'
");
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
                                                        <th>Job No</th>
                                                        <td><?php echo $job->job_no; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Job Date</th>
                                                        <td><?php echo $job->job_date; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Customer</th>
                                                        <td><?php echo $customer->name; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Address</th>
                                                        <td><?php echo $customer->address_line_1 . "<br>" . $customer->address_line_2; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date</th>
                                                        <td><?php echo $job->job_date; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Phone No</th>
                                                        <td><?php echo $customer->phone; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>City</th>
                                                        <td><?php echo $customer->city; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>GST No</th>
                                                        <td><?php echo $customer->gst_no; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Technician</th>
                                                        <td><?php echo $employee->name; ?></td>
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
                                                        <th>Brand</th>
                                                        <th>Qty</th>
                                                        <th>Rate</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $grand_total = 0;
                                                    foreach ($spare_issue_item->fetch_all(MYSQLI_ASSOC) as $i => $item) { 
                                                        $grand_total += $item['total'];
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $i + 1; ?></td>
                                                            <td><?php echo $item['Name']; ?></td>
                                                            <td><?php echo $item['Brand']; ?></td>
                                                            <td><?php echo $item['qty']; ?></td>
                                                            <td><?php echo $item['rate']; ?></td>
                                                            <td><?php echo $item['total']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php foreach ($labour_entry->fetch_all(MYSQLI_ASSOC) as $i => $item) { 
                                                        $grand_total += $item['rate'];
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $spare_item_count + $i + 1; ?></td>
                                                            <td><?php echo $item['title']; ?></td>
                                                            <td>-</td>
                                                            <td>1</td>
                                                            <td><?php echo $item['rate']; ?></td>
                                                            <td><?php echo $item['rate']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <th colspan="5" style="text-align:right">Grand Total</th>
                                                        <th><?php echo $grand_total; ?></th>
                                                    </tr>
                                                </tbody>
                                            </table><br>
                                            <div class="col-md-12 mb-3">
                                                <a href="javascript:history.back()" class="btn btn-primary">Back</a>
                                            </div>
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