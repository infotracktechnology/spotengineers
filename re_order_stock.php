<?php
ob_start();
session_start();
include "config.php";

// Check user session
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$sql = "SELECT i.item_id AS item_id,i.name AS item_name, i.brand AS item_brand,i.model AS item_model, (i.qty + COALESCE(p.purchase_qty, 0) - COALESCE(pr.purchase_return_qty, 0) 
- COALESCE(s.sale_qty, 0) - COALESCE(si.issue_qty, 0)) AS closing_stack, i.re_order AS re_order  
FROM 
items i LEFT JOIN (
SELECT item_id, SUM(quantity) AS purchase_qty
FROM purchase_items
GROUP BY item_id
) p ON i.item_id = p.item_id  
LEFT JOIN (
SELECT item_id, SUM(qty) AS purchase_return_qty
FROM purchase_returns_items
GROUP BY item_id
) pr ON i.item_id = pr.item_id  
LEFT JOIN (
SELECT item_id, SUM(qty) AS sale_qty
FROM sales_items
GROUP BY item_id
) s ON i.item_id = s.item_id  
LEFT JOIN (
SELECT spare_id, SUM(qty) AS issue_qty
FROM spare_issue_item
GROUP BY spare_id
) si ON i.item_id = si.spare_id 
WHERE (i.qty + COALESCE(p.purchase_qty, 0) - COALESCE(pr.purchase_return_qty, 0) 
- COALESCE(s.sale_qty, 0) - COALESCE(si.issue_qty, 0)) <= i.re_order
GROUP BY 
i.item_id, i.name, i.brand, i.model, i.qty  

ORDER BY 
i.name;";
$result = $con->query($sql);
// die((json_encode($result)));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Re-Order Stock Report | Spot Engineers</title>
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
                                        <h4 class="col-deep-purple m-0">Re-Order Stock Report</h4>
                                    </div>
                                    <div class="card-body">
                                      
                                        <div class="table-responsive">
                                            <table class="table table-striped table-sm" id="tableExport" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Item ID</th>
                                                        <th>Item Name</th>
                                                        <th>Item Brand</th>
                                                        <th>Item Model</th>
                                                        <th>Closing Stock</th>
                                                        <th>Re-Order Stock</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 1;
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<tr>
                                                            <td>" . $i . "</td>
                                                            <td>" . $row['item_id'] . "</td>
                                                            <td>" . $row['item_name'] . "</td>
                                                            <td>" . $row['item_brand'] . "</td>
                                                            <td>" . $row['item_model'] . "</td>
                                                            <td>" . $row['closing_stack'] . "</td>
                                                            <td>" . $row['re_order'] . "</td>
                                                        </tr>";
                                                        $i++;
                                                    }
                                                    ?>
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
    <script src="assets/bundles/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
<script>
    $(document).ready(function() {
        $('#tableExport').DataTable({
            dom: 'Bfrtip',
            buttons: [
            'excel', 'pdf'
            ]
        });
 
      
    });
</script>

</html>