<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

include "config.php";

$items = [];
$query = "SELECT * FROM items";
$result = mysqli_query($con, $query);

if ($result) {
    while ($row = mysqli_fetch_object($result)) {
        $items[] = $row;
    }
} else {
    echo "Error: " . mysqli_error($con);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Item Master</title>
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
                                                <h6 class="col-deep-purple">Spare Details</h6>
                                            </div>
                                            <div class="col-2 mb-3">
                                                <a href="item-create.php" class="btn btn-success text-white btn-block">Add Item</a>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm" id="myTable">
                                                <thead>
                                                    <tr role="row">
                                                        <th>S.No</th>
                                                        <th>Name</th>
                                                        <th>Brand</th>
                                                        <th>Model</th>
                                                        <th>Location</th>
                                                        <th>Qty</th>
                                                        <th>Rack No</th>
                                                        <th>Selling Price</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($items)):
                                                        foreach ($items as $key => $item):
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($key + 1); ?></td>
                                                                <td><?php echo htmlspecialchars($item->name); ?></td>
                                                                <td><?php echo htmlspecialchars($item->brand); ?></td>
                                                                <td><?php echo htmlspecialchars($item->model); ?></td>
                                                                <td><?php echo htmlspecialchars($item->location); ?></td>
                                                                <td><?php echo htmlspecialchars($item->qty); ?></td>
                                                                <td><?php echo htmlspecialchars($item->rack_no); ?></td>
                                                                <td><?php echo htmlspecialchars($item->selling_price); ?></td>
                                                                <td>
                                                                    <a href="item-edit.php?id=<?php echo $item->item_id; ?>" class="btn btn-success text-white"><i class="fa fa-edit"></i></a>
                                                                </td>
                                                                <td>
                                                                    <form action="item-delete.php?id=<?= $item->item_id; ?>" onsubmit="return confirm('Are you sure?');" method="post">
                                                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    else:
                                                        ?>
                                                        <tr>
                                                            <td colspan="9">No items found.</td>
                                                        </tr>
                                                    <?php
                                                    endif;
                                                    ?>
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