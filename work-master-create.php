<?php
ob_start();
session_start();
include "config.php";
if (!isset($_SESSION['username'])) {
  header("location:index.php");
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Work Master</title>
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
  <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css">
  <script src="//unpkg.com/alpinejs" defer></script>

</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <?php require('sidebar.php'); ?>
      <!-- Main Content -->
      <div class="main-content" x-data="app">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-header">
                    <h4 class="col-deep-purple m-0">Work Master</h4>
                  </div>
                  <div class="card-body">
                  <form method="post" id="myForm" action="work-master-create.php" enctype="multipart/form-data" x-data="{ customerType: '' }">
                 <div class="row">
                   <div class="col-md-3 form-group">
                     <label class="col-blue">Title</label>
                     <input type="text" name="title" id="name" class="form-control form-control-sm" required>
                   </div>

                   <div class="col-md-3 form-group">
                     <label class="col-blue">Category</label>
                     <input type="text" name="category" class="form-control form-control-sm" >
                   </div>

                   <div class="col-md-3 form-group">
                     <label class="col-blue">Amount</label>
                     <input type="text" name="amount" class="form-control form-control-sm" required>
                   </div>

                

  <div class="col-md-12">
      <button type="submit" class="btn btn-success">Submit</button>
  </div>
               </form>

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
    <script src="assets/bundles/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
<script>
 
  </script>
</html>