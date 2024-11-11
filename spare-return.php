<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

include "config.php";

// Default dates: start of the current month and today's date
$start_date = date('Y-m-01'); // Default start date: 1st day of current month
$end_date = date('Y-m-d');    // Default end date: today's date

// If `start_date` and `end_date` are passed via GET, use them
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    // Validate the date format (YYYY-MM-DD)
    $start_date = date('Y-m-d', strtotime($_GET['start_date']));
    $end_date = date('Y-m-d', strtotime($_GET['end_date']));
}

// Prepare the SQL query with parameterized values (to prevent SQL injection)
$sql = "
    SELECT 
        b.job_no,
        b.job_date,
        a.id,
        a.issue_no,
        a.issue_date,
        c.name,
        c.phone
    FROM 
        spare_issue a
    INNER JOIN 
        customer c ON a.customer_id = c.id
    INNER JOIN 
        job_entry b ON a.job_id = b.id
    WHERE 
        a.issue_date BETWEEN ? AND ?  -- Using the dynamic date range
    GROUP BY 
        a.id, b.job_no, b.job_date, a.issue_no, a.issue_date, c.name, c.phone
";

// Prepare and execute the query with bound parameters
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $start_date, $end_date);  // Bind the parameters for date filtering
$stmt->execute();
$result1 = $stmt->get_result();

// Fetch all records as an associative array
$spare_issue = $result1->fetch_all(MYSQLI_ASSOC);

// Close the statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Spares Return</title>
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
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-header">
                                            <h4 class="col-deep-purple m-0 ml-n4">Spare Return</h4>
                                        </div>

                                        <!-- Date Filter Form -->
                                        <form action="spare-return.php" class="row mb-3" method="get">
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

                                        <!-- Table to display results -->
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Job No</th>
                                                        <th>Job Date</th>
                                                        <th>Issue Number</th>
                                                        <th>Issue Date</th>
                                                        <th>Customer</th>
                                                        <th>Phone Number</th>
                                                        <th>Return</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    if (count($spare_issue) > 0) {
                                                        foreach ($spare_issue as $key => $spare): ?>
                                                            <tr>
                                                                <td><?php echo $key + 1; ?></td>
                                                                <td><?php echo $spare['job_no']; ?></td>
                                                                <td><?php echo $spare['job_date']; ?></td>
                                                                <td><?php echo $spare['issue_no']; ?></td>
                                                                <td><?php echo $spare['issue_date']; ?></td>
                                                                <td><?php echo $spare['name']; ?></td>
                                                                <td><?php echo $spare['phone']; ?></td>
                                                                <td>
                                                                    <a href="spare-edit.php?id=<?php echo $spare['id']; ?>" class="btn btn-success text-white">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; 
                                                    } else { ?>
                                                        <tr><td colspan="8" class="text-center">No records found for the selected date range.</td></tr>
                                                    <?php } ?>
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

    <!-- General JS Scripts -->
    <script src="assets/js/app.min.js"></script>
    <!-- JS Libraries -->
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
