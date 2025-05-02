<?php
ob_start();
session_start();
include "config.php";

// Check user session
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

// $twoMonthsAgo = date('Y-m-d', strtotime('-2 months'));
// $twoMonthsAgo = date('Y-m', strtotime('-2 months'));

$today = date('Y-m-d');

$sql = "SELECT j.job_no, j.job_date, c.name AS customer_name, c.phone AS customer_phone, e.name AS employee_name FROM job_entry j LEFT JOIN customer c ON j.customer_id = c.id LEFT JOIN employee e ON j.emp_id = e.id WHERE j.source = 'followup' AND j.job_date= '$today' ORDER BY j.id ASC;";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Today Assigned Calls | Spot Engineers</title>
    <link rel="stylesheet" href="assets/css/app.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/bundles/stepper/stepper.min.css">
    <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css">
    <link rel="stylesheet" href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
    <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php require('sidebar.php'); ?>
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="col-deep-purple m-0">Today Assigned Calls</h4>
                                    </div>
                                    <div class="card-body">
                                      
                                        <div class="table-responsive">
                                            <table class="table table-striped table-sm" id="tableExport" style="width:100%;">
                                                <thead class="tableHeader">
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Job No</th>
                                                        <th>Job Date</th>
                                                        <th>Customer Name</th>
                                                        <th>Customer Phone</th>
                                                        <th>Employee Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tableBody">
                                                    <?php
                                                    $i = 1;
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<tr>
                                                            <td>" . $i . "</td>
                                                            <td>" . $row['job_no'] . "</td>
                                                            <td>" . $row['job_date'] . "</td>
                                                            <td>" . $row['customer_name'] . "</td>
                                                            <td>" . $row['customer_phone'] . "</td>
                                                            <td>" . $row['employee_name'] . "</td>
                                                        </tr>";
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
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/bundles/datatables/datatables.min.js"></script>
    <script src="assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/bundles/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
<script>
    $(document).ready(function() {
        $('#tableExport').DataTable({
            dom: 'Bfrtip',
            buttons: [
            'excel', 'pdf']
        });
 
      
    });
</script>

</html>