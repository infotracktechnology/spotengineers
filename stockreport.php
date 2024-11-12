<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

include "config.php"; 

$query = "
SELECT i.item_id,i.name AS item_name, i.brand AS item_brand,i.model AS item_model,i.qty AS opening_stack,
COALESCE(p.purchase_qty, 0) AS purchase,COALESCE(pr.purchase_return_qty, 0) AS purchase_return,
COALESCE(s.sale_qty, 0) AS sale,COALESCE(si.issue_qty, 0) AS issue,(i.qty + COALESCE(p.purchase_qty, 0) - COALESCE(pr.purchase_return_qty, 0) 
     - COALESCE(s.sale_qty, 0) - COALESCE(si.issue_qty, 0)) AS closing_stack  
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
GROUP BY 
    i.item_id, i.name, i.brand, i.model, i.qty  

ORDER BY 
    i.name;

";

$result = mysqli_query($con, $query); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Stock Report</title>
    <link rel="stylesheet" href="assets/css/app.min.css" />
    <link rel="stylesheet" href="assets/bundles/summernote/summernote-bs4.css" />
    <link rel="stylesheet" href="assets/bundles/jquery-selectric/selectric.css" />
    <link rel="stylesheet" href="assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" />
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/components.css" />
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="assets/css/custom.css" />
    <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css" />
    <link rel="stylesheet" href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css" />
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <script src="//unpkg.com/alpinejs" defer></script>
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
                                    <h6 class="col-deep-purple m-0 mr-n2" style="padding-bottom: 20px;">Stock Report</h6>
                                       <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="myTable" style="width:100%;">
                                                <thead>
                                                    <tr role="row">
                                                        <th>S.No</th>
                                                        <th>Name</th>
                                                        <th>Brand</th>
                                                        <th>Model</th>
                                                        <th>Opening Stock</th>
                                                        <th>Purchase</th>
                                                        <th>Purchase Return</th>
                                                        <th>Sale</th>
                                                        <th>Issue</th>
                                                        <th>Closing Stock</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $sno = 1;
                                                    while($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $sno++; ?></td>
                                                            <td><?php echo $row['item_name']; ?></td>
                                                            <td><?php echo $row['item_brand']; ?></td>
                                                            <td><?php echo $row['item_model']; ?></td>
                                                            <td><?php echo $row['opening_stack']; ?></td>
                                                            <td><?php echo $row['purchase']; ?></td>
                                                            <td><?php echo $row['purchase_return']; ?></td>
                                                            <td><?php echo $row['sale']; ?></td>
                                                            <td><?php echo $row['issue']; ?></td>
                                                            <td><?php echo $row['closing_stack']; ?></td>
                                                        </tr>
                                                    <?php } ?>
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
   <!-- General JS Scripts -->
   <script src="assets/js/app.min.js"></script>
  <!-- JS Libraies -->
 <!-- Page Specific JS File -->
 <script src="assets/bundles/datatables/datatables.min.js"></script>
  <script src="assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/bundles/datatables/export-tables/dataTables.buttons.min.js"></script>
  <script src="assets/bundles/datatables/export-tables/buttons.flash.min.js"></script>
  <script src="assets/bundles/datatables/export-tables/jszip.min.js"></script>
  <script src="assets/bundles/datatables/export-tables/pdfmake.min.js"></script>
  <script src="assets/bundles/datatables/export-tables/vfs_fonts.js"></script>
  <script src="assets/bundles/datatables/export-tables/buttons.print.min.js"></script>
  <script src="assets/js/page/datatables.js"></script>
  <script src="assets/bundles/select2/dist/js/select2.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>


  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  
  <script src="assets/js/custom.js"></script>
    <script>
        const table = $('#myTable').DataTable({
           "paging": false,
            "ordering": false,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    </script>
</body>
</html>
