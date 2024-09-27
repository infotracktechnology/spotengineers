<?php
ob_start();
session_start();
include "config.php";
if (!isset($_SESSION['username'])) {
  header("location:index.php");
  exit;
}

$id = $_GET['id'];
$customer = [];
$query = "SELECT * FROM customer WHERE id = '$id'";
$result = mysqli_query($con, $query);
if ($result) {
  while ($row = mysqli_fetch_object($result)) {
    $customer[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Customer Master</title>
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
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-header">
                    <h4 class="col-deep-purple m-0">Customer</h4>
                  </div>
                  <div class="card-body">
                  <form method="post" id="myForm" action="customer-update.php?id=<?= $id; ?>" enctype="multipart/form-data" x-data="{ customerType: '<?= $customer[0]->type; ?>' }">
                       <div class="row">
                           <div class="col-md-3 form-group">
                               <label class="col-blue">Name</label>
                               <input type="text" name="name" id="name" class="form-control form-control-sm" value="<?= $customer[0]->name; ?>" required>
                           </div>

                           <div class="col-md-3 form-group">
                               <label class="col-blue">Type</label>
                               <select name="type" id="type" class="form-control form-control-sm" x-model="customerType" required>
                                   <option value="">Select Type</option>
                                   <option value="B2C" <?= ($customer[0]->type == 'B2C') ? 'selected' : ''; ?>>B2C</option>
                                   <option value="B2B" <?= ($customer[0]->type == 'B2B') ? 'selected' : ''; ?>>B2B</option>
                               </select>
                           </div>

                           <div class="col-md-3 form-group">
                               <label class="col-blue">Phone</label>
                               <input type="text" name="phone" value="<?= $customer[0]->phone; ?>" class="form-control form-control-sm" required>
                           </div>

                           <div class="col-md-3 form-group">
                               <label class="col-blue">Address Line 1</label>
                               <input type="text" name="address_line_1" value="<?= $customer[0]->address_line_1; ?>" class="form-control form-control-sm"                    required>
                           </div>

                           <div class="col-md-3 form-group">
                               <label class="col-blue">Address Line 2</label>
                               <input type="text" name="address_line_2" value="<?= $customer[0]->address_line_2; ?>" class="form-control form-control-sm"                    required>
                           </div>

                           <div class="col-md-3 form-group">
                               <label class="col-blue">City</label>
                               <input type="text" name="city" value="<?= $customer[0]->city; ?>" class="form-control form-control-sm" required>
                           </div>

                           <!-- GST No Field, controlled by Alpine.js -->
                           <div class="col-md-3 form-group" x-show="customerType === 'B2B'">
                               <label class="col-blue">GST No</label>
                               <input type="text" name="gst_no" id="gst_no" value="<?= $customer[0]->gst_no; ?>" class="form-control form-control-sm">
                           </div>

                           <div class="col-md-3 mt-4 form-group">
                               <button type="submit" id="Submit" class="btn btn-success text-white" name="submit">Submit</button>
                           </div>
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

</html>
