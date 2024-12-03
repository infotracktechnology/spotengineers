<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}
include "config.php";



// Get the emp_id from the URL (use emp_id to fetch the correct records)
$emp_id = isset($_GET['emp_id']) ? mysqli_real_escape_string($con, $_GET['emp_id']) : '';
$employee = $con->query("SELECT * FROM employee WHERE id = '$emp_id'")->fetch_assoc();

// Fetch employee advance details only if emp_id is set
$advances = [];
if ($emp_id) {
    $advance_query = "SELECT ea.id, ea.emp_id, ea.date, ea.amount, e.name 
                      FROM employee_advance ea 
                      JOIN employee e ON ea.emp_id = e.id 
                      WHERE ea.emp_id = '$emp_id'";

    $advance_result = mysqli_query($con, $advance_query);

    if ($advance_result) {
        $advances = mysqli_fetch_all($advance_result, MYSQLI_ASSOC);
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Handle POST request to save new advance details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['advance_amount'])) {
    $emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);
    $advance_date = mysqli_real_escape_string($con, $_POST['advance_date']);
    $advance_amount = mysqli_real_escape_string($con, $_POST['advance_amount']);

    $insert_advance_query = "INSERT INTO employee_advance (emp_id, date, amount) 
                             VALUES ('$emp_id', '$advance_date', '$advance_amount')";
    $insert_result = mysqli_query($con, $insert_advance_query);

    if ($insert_result) {
       echo"<script>alert('Advance added successfully!'); window.location.href = 'employee.php';</script>";
       exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Employee Master</title>
    <link rel="stylesheet" href="assets/css/app.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
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
      <div class="main-content" x-data="app">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-header">
                    <h4 class="col-deep-purple m-0">Employee Advance Details</h4>
                  </div>
                  <div class="card-body">
                  <form method="post" id="myForm" action="employee-advance.php" enctype="multipart/form-data">
                 <div class="row">
                                                <!-- Reduced field sizes for a more compact layout -->
                                                <div class="col-md-3 form-group">
                                                    <label>Employee</label>
                                                    <input type="hidden" name="emp_id" value="<?= $emp_id; ?>">
                                                    <input type="text" name="name"  value="<?= $employee['name']; ?>" id="name" class="form-control form-control-sm char" required>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label for="advance_date">Advance Date</label>
                                                    <input type="date" name="advance_date" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label for="advance_amount">Advance Amount</label>
                                                    <input type="number" name="advance_amount" class="form-control form-control-sm">
                                                   
                                                </div>
                                                <div class="col-md-3 form-group">
                                            <button type="submit" class="btn btn-primary mt-4">Add Advance</button> 
                                            </div>
                                            </div>
                                            
                                        </form>

                                        <!-- Section for displaying employee advance data -->
                                        <?php if ($emp_id) { ?>
                                            <h6 class="col-deep-purple mt-5">Employee Advance Details</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm" id="myTable">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>S.No</th>
                                                            <th>Name</th>
                                                            <th>Date</th>
                                                            <th>Advance Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if ($advances) {
                                                            foreach ($advances as $key => $value) { ?>
                                                                <tr>
                                                                    <td><?= $key + 1 ?></td>
                                                                    <td><?= $value['name'] ?></td>
                                                                    <td><?= $value['date'] ?></td>
                                                                    <td><?= $value['amount'] ?></td>
                                                                </tr>
                                                            <?php }
                                                        } else {
                                                            echo "<tr><td colspan='4'>No records found for this employee.</td></tr>";
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } else { ?>
                                            <p>Please select an employee to view their advance details.</p>
                                        <?php } ?>
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
