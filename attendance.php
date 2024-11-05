<?php
ob_start();
session_start();
include "config.php";

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}


$employees = [];
$result = $con->query("SELECT id, name FROM employee");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendance_date = $_POST['date'];

    // Check if attendance for this date already exists
    $checkStmt = $con->prepare("SELECT COUNT(*) FROM attendance WHERE attendance_date = ?");
    $checkStmt->bind_param("s", $attendance_date);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {

        $_SESSION['attendance_alert'] = 'Attendance has already been recorded for this date.';
        header("Location: attendance.php");
        exit;
    } else {
        // Insert attendance for each employee if no record exists for the date
        foreach ($_POST['status'] as $emp_id => $attendance) {
            $stmt = $con->prepare("INSERT INTO attendance (emp_id, attendance_date, attendance, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iss", $emp_id, $attendance_date, $attendance);

            if (!$stmt->execute()) {
                echo "<script>alert('Error recording attendance for employee ID $emp_id: " . $stmt->error . "');</script>";
            }

            $stmt->close();
        }


        header("Location: attendance.php?success=1");
        exit;
    }
}


if (isset($_SESSION['attendance_alert'])) {
    echo "<script>alert('" . $_SESSION['attendance_alert'] . "');</script>";
    unset($_SESSION['attendance_alert']);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Attendance</title>
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
                                        <h4 class="col-deep-purple m-0">Attendance</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" id="myForm" action="" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Date</label>
                                                    <input type="date" name="date" class="form-control form-control-sm" required
                                                        value="<?php echo date('Y-m-d'); ?>">
                                                </div>

                                            </div>
                                            <table class="table table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>SI</th>
                                                        <th>Employee Name</th>
                                                        <th>Attendance Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $si = 1;
                                                    foreach ($employees as $employee): ?>
                                                        <tr>
                                                            <td><?= $si++ ?></td>
                                                            <td><?= htmlspecialchars($employee['name']) ?></td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <button type="button" class="btn btn-outline-info btn-sm active" onclick="setStatus('present', <?= htmlspecialchars($employee['id']) ?>)" style="margin-right: 14px;">P</button>
                                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="setStatus('absent', <?= htmlspecialchars($employee['id']) ?>)" style="margin-right: 14px;">A</button>
                                                                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="setStatus('half_day', <?= htmlspecialchars($employee['id']) ?>)" style="margin-right: 14px;">HL</button>
                                                                    <input type="hidden" name="status[<?= htmlspecialchars($employee['id']) ?>]" value="present" id="status-<?= htmlspecialchars($employee['id']) ?>">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <div class="col-md-2 form-group">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </form>
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

    <script>
        function setStatus(status, empId) {
            // Update the hidden input field with the selected status
            document.getElementById(`status-${empId}`).value = status;

            // Get all buttons for the current employee and remove the active class
            const buttons = document.querySelectorAll(`button[onclick*="${empId}"]`);
            buttons.forEach(button => button.classList.remove('active'));

            // Add the active class to the selected button
            event.target.classList.add('active');
        }
    </script>


</body>

</html>