<?php
ob_start();
session_start();
include "config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Spares Return</title>
    <!-- General CSS Files -->
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
    <div id="app" ng-app="myApp" ng-controller="issueController">
        <div class="main-wrapper main-wrapper-1">
            <?php require('sidebar.php'); ?>
            <!-- Main Content -->
            <div class="main-content" x-data="app">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form method="post" name="myForm" action="sales_report.php" enctype="multipart/form-data">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="col-deep-purple m-0">Spare Return</h4>
                                        </div>
                                     
                                  
                       

                                    <div class="card-body">
                                    <div class="table-responsive">
  <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
    <thead>
      <tr>
        <th>S.No</th>
        <th>Job No</th>
        <th>Job Date</th>
        <th>Issue Number</th>
        <th>Issue Date</th>
        <th>Customer</th>
        <th>Phone Number</th>
        <th>Return</th>
      </tr>
    </thead>
    <tbody>
      <?php 
    $result1 = $con->query("
    SELECT 
        b.job_no,
        b.job_date,
        a.issue_no,
        a.issue_date,
        c.name,
        c.phone
    FROM 
        spare_issue a
    INNER JOIN 
        customer c ON a.customer_id = c.id
    INNER JOIN 
        job_entry b ON a.job_id = b.id
    GROUP BY 
        a.id, b.job_no, b.job_date, a.issue_no, a.issue_date, c.name, c.phone
");

      $spare_issue = $result1->fetch_all(MYSQLI_ASSOC);
      ?>

      <?php foreach ($spare_issue as $key => $spare): ?>
        <tr >
          <td><?php echo $key+1; ?></td>
          <td><?php echo $spare['job_no']; ?></td>
          <td><?php echo $spare['job_date']; ?></td>
          <td><?php echo $spare['issue_no']; ?></td>
          <td><?php echo $spare['issue_date']; ?></td>
          <td><?php echo $spare['name']; ?></td>
          <td><?php echo $spare['phone']; ?></td>
          <td><a href="spare-edit.php?issue_no=<?php echo $spare['issue_no']; ?>" class="btn btn-success text-white">
        <i class="fa fa-edit"></i>
      </a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

                  </div>

                 

                            </div>
                            </form>
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

  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  
  <script src="assets/js/custom.js"></script>

<script>

</script>

</body>

</html>