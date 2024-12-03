<?php
ob_start();
session_start();
include "config.php";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
  header("location:index.php");
  exit;
}

// Fetch employee data based on the ID parameter
$id = $_GET['id'];
$employee = [];
$query = "SELECT * FROM employee WHERE id = '$id'";
$result = mysqli_query($con, $query);
if ($result) {
  $employee = mysqli_fetch_object($result); // Fetch a single record as object
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Employee Profile</title>
    <link rel="stylesheet" href="assets/css/app.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css">
    <link rel="stylesheet" href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
    <style>
        .author-box-picture {
            width: 100px; /* Set a fixed width */
            height: 100px; /* Set a fixed height */
            object-fit: cover; /* Ensure the image is not distorted */
        }

        .author-box-center {
            text-align: center; /* Center content */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .author-box-name {
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
        }

        .author-box-job {
            font-size: 14px;
            color: gray;
        }
    </style>
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
                            <div class="col-md-4 col-sm-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="author-box-center">
                                            <!-- Display employee's image or default if not set -->
                                            <img alt="Noimage" 
                                                 src="<?= !empty($employee->photo) ? $employee->photo : 'assets/img/users/no image.png' ?>" 
                                                 class="rounded-circle author-box-picture">
                                            <div class="author-box-name">
                                                <a href="#"><?= htmlspecialchars($employee->name) ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Bank Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <p class="clearfix">
                                            <span class="float-left">Account No</span>
                                            <span class="float-right text-muted"><?= htmlspecialchars($employee->acc_no) ?></span>
                                        </p>
                                        <p class="clearfix">
                                            <span class="float-left">IFSC Code</span>
                                            <span class="float-right text-muted"><?= htmlspecialchars($employee->ifsc) ?></span>
                                        </p>
                                        <p class="clearfix">
                                            <span class="float-left">Branch</span>
                                            <span class="float-right text-muted"><?= htmlspecialchars($employee->branch) ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <!-- Card for Personal Details (Remains the same) -->
                            <div class="col-md-8 col-sm-12 mb-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Personal Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="py-4">
                                            <p class="clearfix">
                                                <span class="float-left">Birthday</span>
                                                <span class="float-right text-muted"><?= htmlspecialchars($employee->dob) ?></span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Phone</span>
                                                <span class="float-right text-muted"><?= htmlspecialchars($employee->phone) ?></span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Date of Joining</span>
                                                <span class="float-right text-muted"><?= htmlspecialchars($employee->doj) ?></span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Experience</span>
                                                <span class="float-right text-muted"><?= htmlspecialchars($employee->experience) ?></span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Aadhar No</span>
                                                <span class="float-right text-muted"><?= htmlspecialchars($employee->aadhar) ?></span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Pan No</span>
                                                <span class="float-right text-muted"><?= htmlspecialchars($employee->pan) ?></span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Salary</span>
                                                <span class="float-right text-muted"><?= htmlspecialchars($employee->salary) ?></span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Address</span>
                                                <span class="float-right text-muted"><?= htmlspecialchars($employee->address_line_1 . ', ' . $employee->address_line_2 ) ?></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <!-- New Card for Bank Details -->
                            <div class="col-md-4 col-sm-12 mb-3">
                               
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
</body>
</html>
