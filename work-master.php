<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}
include "config.php";
$work = [];
$query = "SELECT * FROM work";
$result = mysqli_query($con, $query);
if ($result) {
    while ($row = mysqli_fetch_object($result)) {
        $work[] = $row;
    }
} else {
    echo "Error: " . mysqli_error($con);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $id = mysqli_real_escape_string($con, $_POST['id']);
   $delete_items = mysqli_query($con, "DELETE FROM work WHERE id = $id");
   if ($delete_items) {
       header("Location: work-master.php");
       exit;
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Work Master</title>
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
                                                <h6 class="col-deep-purple">Work Schedule Master Details</h6>
                                            </div>
                                            <div class="col-2 mb-3">
                                                <a href="work-master-create.php" class="btn btn-success text-white btn-block">Add Work</a>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm" id="myTable">
                                                <thead>
                                                    <tr role="row">
                                                        <th>S.No</th>
                                                        <th>Title</th>
                                                        <th>Category</th>
                                                        <th> Amount</th>
                                                         <th>Edit</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($work as $key => $value) { ?>
                                                    <tr>
                                                        <td><?= $key + 1 ?></td>
                                                        <td><?= $value->title ?></td>
                                                        <td><?= $value->category ?></td>
                                                        <td><?= $value->amount ?></td>
                                                        <td><a href="work-master-edit.php?id=<?= $value->id ?>" class="btn btn-success text-white"><i class="fas fa-edit"></i></a></td>
                                                        <td><form action="work-master.php" method="post" onsubmit="return confirm('Are you sure you want to delete this work?');"><button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button><input type="hidden" name="id" value="<?= $value->id ?>"></ /></form></td>
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