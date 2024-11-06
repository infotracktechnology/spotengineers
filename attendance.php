<?php
ob_start();
session_start();
include "config.php";

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

// Fetch all employees
$employees = [];
$result = $con->query("SELECT id, name FROM employee");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Check if a date is selected
//$attendance_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Fetch attendance for the selected date
// $attendanceData = [];
// if ($attendance_date) {
//     $query = "SELECT emp_id, attendance FROM attendance WHERE attendance_date = '$attendance_date'";
//     $result = $con->query($query);
//     if ($result) {
//         while ($row = $result->fetch_assoc()) {
//             $attendanceData[$row['emp_id']] = $row['attendance'];
//         }
//     }
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendance_date = $_POST['attendance_date'];

    // Delete existing attendance for the selected date
    $con->query("DELETE FROM attendance WHERE attendance_date = '$attendance_date'");
    // Insert attendance for each employee without checking for existing attendance
    foreach ($_POST['status'] as $emp_id => $attendance) {
        echo "Inserting status for employee $emp_id: $attendance<br>"; // Debugging line
        $attendance = $con->query("INSERT INTO attendance (emp_id, attendance_date, attendance) VALUES ($emp_id, '$attendance_date', '$attendance')");
    }

    header("Location: attendance.php?date=$attendance_date&success=1");
    exit;
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
                                        <form method="post" id="myForm" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Date</label>
                                                    <input type="date" name="attendance_date" class="form-control form-control-sm" max="<?= date('Y-m-d') ?>"  value="<?= date('Y-m-d') ?>"   required />

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
                                                                    <button type="button" class="btn btn-outline-info btn-sm <?php echo isset($attendanceData[$employee['id']]) && $attendanceData[$employee['id']] == 'present' ? 'active' : ''; ?>" onclick="setStatus('present', <?= htmlspecialchars($employee['id']) ?>)" style="margin-right: 14px;">P</button>
                                                                    <button type="button" class="btn btn-outline-danger btn-sm <?php echo isset($attendanceData[$employee['id']]) && $attendanceData[$employee['id']] == 'absent' ? 'active' : ''; ?>" onclick="setStatus('absent', <?= htmlspecialchars($employee['id']) ?>)" style="margin-right: 14px;">A</button>
                                                                    <button type="button" class="btn btn-outline-warning btn-sm <?php echo isset($attendanceData[$employee['id']]) && $attendanceData[$employee['id']] == 'half_day' ? 'active' : ''; ?>" onclick="setStatus('half_day', <?= htmlspecialchars($employee['id']) ?>)" style="margin-right: 14px;">HL</button>
                                                                    
                                                                    <button type="button" class="btn btn-outline-primary btn-sm <?php echo isset($attendanceData[$employee['id']]) && $attendanceData[$employee['id']] == 'sick_leave' ? 'active' : ''; ?>" onclick="setStatus('sick_leave', <?= htmlspecialchars($employee['id']) ?>)" style="margin-right: 14px;">SL</button>
                                                                    <input type="hidden" name="status[<?= htmlspecialchars($employee['id']) ?>]" value="<?php echo isset($attendanceData[$employee['id']]) ? $attendanceData[$employee['id']] : 'present'; ?>" id="status-<?= htmlspecialchars($employee['id']) ?>">
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
            // Log status and employee ID to the console
            console.log('Setting status for empId ' + empId + ': ' + status);

            // Update the hidden input field with the selected status
            document.getElementById(`status-${empId}`).value = status;

            // Get all buttons for the current employee and remove the active class
            const buttons = document.querySelectorAll(`button[onclick*="${empId}"]`);
            buttons.forEach(button => button.classList.remove('active'));

            // Add the active class to the selected button
            event.target.classList.add('active');
        }

        // Log form data before submitting to check if 'sick_leave' is sent
        document.getElementById('myForm').addEventListener('submit', function(e) {
            const selectedDate = document.querySelector('input[name="date"]').value;
            const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowDate = tomorrow.toISOString().split('T')[0]; // Get tomorrow's date in YYYY-MM-DD format

            // Check if selected date is today or tomorrow
            if (selectedDate !== today && selectedDate === tomorrowDate) {
                alert("You cannot record attendance for tomorrow.");
                e.preventDefault(); // Prevent form submission
            }
        });

        // function getAttendanceData(date) {
        //     $.ajax({
        //         type: 'GET',
        //         url: 'attendance.php',
        //         data: {
        //             date: date
        //             },
        //             success: function(data) {
        //                 $('#tableExport').html(data);
        //             }
        //             });
        // }        
    </script>

</body>

</html>