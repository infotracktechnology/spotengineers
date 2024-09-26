<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

include "config.php";

$customer = [];
$query = "SELECT * FROM customer";
$result = mysqli_query($con, $query);

if ($result) {
    while ($row = mysqli_fetch_object($result)) {
        $customer[] = $row;
    }
} else {
    echo "Error: " . mysqli_error($con);
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = $_POST['id'];
    $query = "DELETE FROM customer WHERE id = $id";
    $result = mysqli_query($con, $query);
    header("location:customer.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Customer Master</title>
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
                                            <div class="col-10 mb-3">
                                                <h6 class="col-deep-purple">Customer Details</h6>
                                            </div>
                                            <div class="col-2 mb-3">
                                                <a href="customer-create.php" class="btn btn-success text-white btn-block">Add Customer</a>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm" id="myTable">
                                                <thead>
                                                    <tr role="row">
                                                        <th>S.No</th>
                                                        <th>Name</th>
                                                        <th>Type</th>
                                                        <th>Phone No</th>
                                                        <th>Address Line 1</th>
                                                        <th>Address Line 2</th>
                                                        <th>City</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($customer as $key => $value) { ?>
                                                        <tr>
                                                            <td><?= $key + 1 ?></td>
                                                            <td><?= $value->name ?></td>
                                                            <td><?= $value->type ?></td>
                                                            <td><?= $value->phone ?></td>
                                                            <td><?= $value->address_line_1 ?></td>
                                                            <td><?= $value->address_line_2 ?></td>
                                                            <td><?= $value->city ?></td>
                                                            <td><a href="customer-edit.php?id=<?= $value->id ?>" class="btn btn-success text-white"><i class="fa fa-edit"></i></a></td>
                                                            <td><form action="customer.php" method="post" onsubmit="return confirm('Are you sure you want to delete this customer?');"><input type="hidden" name="id" value="<?= $value->id ?>"><button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button></form></td>
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