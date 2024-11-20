<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

include "config.php";

$start_date = date('Y-m-01');
$end_date = date('Y-m-d');
if (isset($_GET['start_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
}
$job_status = array('pending' => 'badge-danger', 'spare issue' => 'badge-warning', 'completed' => 'badge-success');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Spot Engineers | Job Entry</title>
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
                                                <h6 class="col-deep-purple">Job Register</h6>
                                            </div>
                                            <div class="col-md-2 col-sm-12 mb-3">
                                                <a href="labour-entry.php" class="btn btn-success text-white btn-block">Add Job</a>
                                            </div>
                                        </div>

                                        <form action="job-entry.php" class="row mb-3" method="get">
                                                <div class="col-md-3 col-sm-12">
                                                    <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="form-control form-control-sm" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="form-control form-control-sm" />
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <button type="submit" class="btn btn-success">Search</button>
                                                </div>
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table table-sm" id="myTable">
                                                <thead>
                                                    <tr role="row">
                                                        <th>S.No</th>
                                                        <th>Job No</th>
                                                        <th>Job Date</th>
                                                        <th>Customer</th>
                                                        <th>Customer Phone</th>
                                                        <th>Technician</th>
                                                        <th>Status</th>
                                                        <th>Spare Issue</th>
                                                        <th>Print</th>
                                                    </tr> 
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $result = $con->query("SELECT a.*,b.name,b.phone,c.name emp_name,c.doj FROM job_entry a join customer b on a.customer_id=b.id join employee c on a.emp_id=c.id where job_date between '$start_date' and '$end_date'")->fetch_all(MYSQLI_ASSOC);
                                                    foreach ($result as $key => $job):
                                                    ?>
                                                            <tr>
                                                                <td><?php echo $key + 1 ?></td>
                                                                <td><?php echo $job['job_no'] ?></td>
                                                                <td><?php echo $job['job_date'] ?></td>
                                                                <td><?php echo $job['name'] ?></td>
                                                                <td><?php echo $job['phone'] ?></td>
                                                                <td><?php echo $job['emp_name'] ?></td>
                                                                <td><?= "<span class='badge {$job_status[$job['status']]}'>$job[status]</span>";  ?></td>    
                                                            <?php
                                                            if($job['status'] != 'completed'):
                                                            ?>
                                                            <td><a href="spare-issue.php?job_id=<?php echo $job['id']; ?>" class="btn btn-success text-white"><i class="fa fa-plus"></i></a>
                                                            </td>
                                                            <td>
                                                            <a href="bills.php?bill_by=Job&value=<?php echo $job['job_no']; ?>" class="btn btn-success text-white"><i class="fa fa-eye"></i></a>
                                                            </td>
                                                            <?php else: ?>
                                                                <td></td>
                                                                <td></td>
                                                            <?php endif; ?>
                                                            </tr>
                                                        <?php
                                                        endforeach;
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