
<?php
ob_start();
session_start();
include "config.php";


if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}


$currentMonth = date('Y-m');  


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['attendance_date'])) {
    $attendance_date = $_POST['attendance_date'];

    $stmt = $con->prepare("SELECT e.name, e.id AS emp_id,
                            COUNT(DISTINCT a.attendance_date) AS month_working_days,  
                            SUM(CASE WHEN a.attendance = 'P' THEN 1 ELSE 0 END) AS present_count,
                            SUM(CASE WHEN a.attendance = 'A' THEN 1 ELSE 0 END) AS absent_count,
                            SUM(CASE WHEN a.attendance = 'SL' THEN 1 ELSE 0 END) AS sl_count,
                            SUM(CASE WHEN a.attendance = 'HL' THEN 1 ELSE 0 END) AS hl_count
                            FROM attendance a
                            JOIN employee e ON a.emp_id = e.id
                            WHERE a.attendance_date LIKE ?
                            GROUP BY e.id");

    $attendance_date .= '%';  
    $stmt->bind_param("s", $attendance_date);  
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = null;  
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Attendance Report</title>
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
                                        <h4 class="col-deep-purple m-0">Attendance Report</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" id="myForm">
                                            <div class="row">
                                               
                                                <div class="col-md-4 form-group">
                                                    <label>Date</label>
                                                    <input type="month" name="attendance_date" value="<?php echo $currentMonth; ?>" class="form-control form-control-sm" required />
                                                </div>
                                               
                                                <div class="col-md-4 form-group align-self-end">
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-sm" id="tableExport" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Employee Name</th>
                                                        <th>Month Working Days</th>
                                                        <th>Present Count</th>
                                                        <th>Absent Count</th>
                                                        <th>SL Count</th>
                                                        <th>HL Count</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    
                                                    if ($result) {
                                                        $sno = 1;
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>";
                                                            echo "<td>" . $sno++ . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                                            echo "<td>" . $row['month_working_days'] . "</td>"; 
                                                            echo "<td>" . $row['present_count'] . "</td>";
                                                            echo "<td>" . $row['absent_count'] . "</td>";
                                                            echo "<td>" . $row['sl_count'] . "</td>";
                                                            echo "<td>" . $row['hl_count'] . "</td>";
                                                            echo "</tr>";
                                                        }
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

</html>