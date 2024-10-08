<?php
ob_start();
session_start();
include "config.php";
if (!isset($_SESSION['username'])) {
  header("location:index.php");
  exit;
}

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    $employee = mysqli_query($con, "INSERT INTO employee(name,dob,Phone,address_line_1,address_line_2,city,doj,experience) VALUES( '$name', '$dob', '$phone', '$address_line_1', '$address_line_2', '$city', '$doj', '$experience')");
    header("location:employee.php");
    exit;
  }


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Employee Master</title>
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
                    <h4 class="col-deep-purple m-0">Employee Details</h4>
                  </div>
                  <div class="card-body">
                  <form method="post" id="myForm" action="employee-master-create.php" enctype="multipart/form-data" x-data="{ customerType: '' }">
                 <div class="row">
                   <div class="col-md-3 form-group">
                     <label class="col-blue">Name</label>
                     <input type="text" name="name" id="name" class="form-control form-control-sm" required>
                   </div>

                   <div class="col-md-3 form-group">
                     <label class="col-blue">DOB</label>
                     <input type="date" name="dob" class="form-control form-control-sm" >
                   </div>

                   <div class="col-md-3 form-group">
                     <label class="col-blue">Phone</label>
                     <input type="number" name="Phone" value="" class="form-control form-control-sm" required>
                     <div class="invalid-feedback">Please Enter 10 digits Mobile Number.</div>
                     </div>

                   <div class="col-md-3 form-group">
                     <label class="col-blue">Address Line 1</label>
                     <input type="text"  name="address_line_1" class="form-control form-control-sm" required>
                   </div>

                   <div class="col-md-3 form-group">
                     <label class="col-blue">Address Line 2</label>
                     <input type="text"  name="address_line_2" class="form-control form-control-sm" required>
                   </div>

                   <div class="col-md-3 form-group">
                     <label class="col-blue">City</label>
                     <input type="text"  name="city" class="form-control form-control-sm" required>
                   </div>


                   <div class="col-md-3 form-group">
                     <label class="col-blue">Date of Joining</label>
                     <input type="date"  name="doj" class="form-control form-control-sm" >
                   </div>

                   <div class="col-md-3 form-group">
                     <label class="col-blue">Experience</label>
                     <input type="text"  name="experience" class="form-control form-control-sm" >
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
const phoneInput = document.querySelector('input[name="Phone"]');
phoneInput.addEventListener('input', function () {
  phoneInput.classList.toggle('is-invalid', phoneInput.value.length !== 10 || !/^\d{10}$/.test(phoneInput.value));
});


  </script>
</html>