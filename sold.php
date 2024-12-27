<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

include "config.php";
$query = "SELECT s.id, s.sold_date, a.appliance_name, s.expense, c.name, s.sell_amnt
          FROM sold s
          JOIN customer_appliances a ON s.appliance_id = a.id
          JOIN customer c ON s.seller_id = c.id";
$sold = $con->query($query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Sold</title>
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
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-10 col-sm-12 mb-3">
                                                <h6 class="col-deep-purple">Sold Details</h6>
                                            </div>
                                            <div class="col-md-2 col-sm-12 mb-3">
                                                <a href="add-sold.php" class="btn btn-success text-white btn-block">Add Sold</a>
                                            </div>
                                        </div>
                                        <form action="sold.php" class="row mb-3" method="get">
                                            <div class="col-md-3 col-sm-12">
                                                <input type="date" name="start_date" value="" class="form-control form-control-sm" />
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <input type="date" name="end_date" value="" class="form-control form-control-sm" />
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <button type="submit" class="btn btn-success">Search</button>
                                            </div>
                                        </form>


                                        <div class="table-responsive">


                                            <table class="table table-sm" id="myTable">
                                                <thead>
                                                    <tr role="row">
                                                        <th>S.No</th>
                                                        <th>Sold date</th>
                                                        <th>Appliance Name</th>
                                                        <th>Expense Amount</th>
                                                        <th>Seller Name</th>
                                                        <th>Sell Amount</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    $i = 1;
                                                    while ($row = $sold->fetch_assoc()) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo $row['sold_date']; ?></td>
                                                            <td><?php echo $row['appliance_name']; ?></td>
                                                            <td><?php echo $row['expense']; ?></td>
                                                            <td><?php echo $row['name']; ?></td>
                                                            <td><?php echo $row['sell_amnt']; ?></td>
                                                            <td>

                                                                <form action="sold.php" method="post" onsubmit="return confirm('Are you sure you want to delete this Record?');">
                                                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                                    <button class="btn btn-danger" type="submit">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </td>

                                                        </tr>
                                                    <?php
                                                        $i++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>

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