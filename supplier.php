<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

include "config.php";
$suppliers = [];
$query = "SELECT * FROM suppliers";
$result = mysqli_query($con, $query);

if ($result) {
    while ($row = mysqli_fetch_object($result)) {
        $suppliers[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Supplier Master</title>
    <!-- General CSS Files -->
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
            <div class="main-content">
                <section class="section">
                    <div class="section-body"> 
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-primary">
                                    <!-- Add card hedaer -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-10 mb-3">
                                                <h6 class="col-deep-purple">Supplier Details</h6>
                                            </div>
                                            <div class="col-2 mb-3">
                                                <a href="supplier-create.php" class="btn btn-success text-white btn-block">Add Supplier</a>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-sm" id="myTable">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>#</th>
                                                            <th>Supplier Name</th>
                                                            <th>Address</th>
                                                            <th>Account No</th>
                                                            <th>Mobile No</th>
                                                            <th>GST NO</th>
                                                            <th>Edit</th>
                                                            <th>Delete</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php foreach ($suppliers  as $key => $supplier): ?>
                                                            <tr>
                                                                <td><?= $key + 1; ?></td>
                                                                <td><?= $supplier->supplier_name; ?></td>
                                                                <td><?= $supplier->address1; ?></td>
                                                                <td><?= $supplier->account_no; ?></td>
                                                                <td><?= $supplier->mobile1; ?></td>
                                                                <td><?= $supplier->gst; ?></td>
                                                                <td>
                                                                    <a href="supplier-edit.php?id=<?= $supplier->supplier_id; ?>" class="btn btn-success text-white"><i class="fas fa-edit"></i></a>
                                                                </td>
                                                                <td>
                                                                    <form action="supplier-delete.php?id=<?= $supplier->supplier_id; ?>" onsubmit="return confirm('Are you sure?');" method="post">
                                                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
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