<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}
include "config.php";
$query = "SELECT * FROM employee";
$result = mysqli_query($con, $query);

if ($result) {
    $employee = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($con);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $delete_items = mysqli_query($con, "DELETE FROM employee WHERE id = $id");
    if ($delete_items) {
        header("Location: employee.php");
        exit;
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
                                                <h6 class="col-deep-purple">Employee Details</h6>
                                            </div>
                                            <div class="col-md-2 col-sm-12 mb-3">
                                                <a href="employee-master-create.php" class="btn btn-success text-white btn-block">Add Employee</a>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm" id="myTable">
                                                <thead>
                                                    <tr role="row">
                                                        <th>S.No</th>
                                                        <th>Name</th>
                                                        <th>DOB</th>
                                                        <th>Phone</th>
                                                        <th>Address Line 1</th>
                                                        <th>Address Line 2</th>
                                                        <th>City</th>
                                                        <th>Date of joining</th>
                                                        <th>Experience</th>
                                                        <th>Advance Details</th>
                                                        <th>Edit</th>
                                                        <th>Visit Profile</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($employee as $key => $value) { ?>
                                                    <tr>
                                                        <td><?= $key + 1 ?></td>
                                                        <td><?= $value['name'] ?></td>
                                                        <td><?= $value['dob'] ?></td>
                                                        <td><?= $value['phone'] ?></td>
                                                        <td><?= $value['address_line_1'] ?></td>
                                                        <td><?= $value['address_line_2'] ?></td>
                                                        <td><?= $value['city'] ?></td>
                                                        <td><?= $value['doj'] ?></td>
                                                        <td><?= $value['experience'] ?></td>
                                                        <td><a href="employee-advance.php?emp_id=<?= $value['id'] ?>" class="btn btn-warning text-white"><i class="fa fa-dollar-sign"></i></a></td>
                                                        <td><a href="employee-master-edit.php?id=<?= $value['id'] ?>" class="btn btn-success text-white"><i class="fa fa-edit"></i></a></td>
                                                        <td><a href="employee-profile.php?id=<?= $value['id'] ?>" class="btn btn-info text-white"><i class="fa fa-eye"></i></a></td>
                                                        <td>
                                                            <form action="employee.php" method="post" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                                                <input type="hidden" name="id" value="<?= $value['id'] ?>">
                                                                <button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
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
