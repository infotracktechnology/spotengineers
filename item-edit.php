<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
  header("location:index.php");
  exit;
}

include 'config.php';
$id = $_GET['id'];
$items = [];
$query = "SELECT * FROM items WHERE item_id = '$id'";
$result = mysqli_query($con, $query);
if ($result) {
    while ($row = mysqli_fetch_object($result)) {
        $items[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Item Master</title>
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
                    <h4 class="col-deep-purple m-0">Update Item Details</h4>
                  </div>
                  <div class="card-body">
                    <form method="post" id="myForm" action="item-update.php?id=<?= $id; ?>" enctype="multipart/form-data">
                      <div class="row">
                        <div class="col-md-3 form-group">
                          <label class="col-blue">Name</label>
                          
                          <input type="text" name="name" id="name" class="form-control form-control-sm char" value="<?= $items[0]->name; ?>" required>
                          <small class="form-text text-danger" id="nameError"></small>
                        </div>
                        <div class="col-md-3 form-group">
                          <label class="col-blue">Brand</label>
                          
                          <input type="text" name="brand" id="brand" value="<?= $items[0]->brand; ?>" class="form-control form-control-sm char" required>
                        </div>
                        <div class="col-md-3 form-group">
                          <label class="col-blue">Model</label>
                          
                          <input type="text" name="model" id="model" value="<?= $items[0]->model; ?>" class="form-control form-control-sm char" required>
                        </div>
                        <div class="col-md-3 form-group">
                          <label class="col-blue">Location</label>
                          
                          <input type="text" name="location" id="location" value="<?= $items[0]->location; ?>" class="form-control form-control-sm" required>
                        </div>

                        
                        <div class="col-md-3 form-group">

                          <label class="col-blue">HSN No</label>
                          
                          <input type="text" name="hsn" id="hsn" value="<?= $items[0]->hsn; ?>" class="form-control form-control-sm numberk" required>
                        </div>


                        <div class="col-md-3 form-group">
                          <label class="col-blue">Rack NO</label>
                          
                          <input type="text" name="rack_no" value="<?= $items[0]->rack_no; ?>" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group col-lg-2">
                          <label class="col-blue">UOM</label>
                          
                          <select class="form-control form-control-sm select2" name="uom" id="uom" style="width: 100%;" required>
                            <option value="<?= $items[0]->uom; ?>"><?= $items[0]->uom; ?></option>
                            <option value="Nos">Nos</option> 
                          </select>
                        </div>
                        <div class="col-md-2 form-group">
                          <label class="col-blue">M.R.P.</label>
                          
                          <input type="number" name="mrp" id="mrp" value="<?= $items[0]->mrp; ?>" class="form-control form-control-sm numberk" required>
                          <small class="form-text text-danger" id="mrpError"></small>
                        </div>
                        <div class="col-md-2 form-group">
                          <label class="col-blue">Selling Price</label>
                          
                          <input type="number" name="selling_price" id="selling_price" value="<?= $items[0]->selling_price; ?>" class="form-control form-control-sm numberk" required>
                        </div>
                        <div class="col-md-2 form-group">
                          <label class="col-blue">Qty</label>
                          
                          <input type="number" name="qty" id="qty" min="0" value="<?= $items[0]->qty; ?>" class="form-control form-control-sm numberk" required>
                          <small class="form-text text-danger" id="qtyError"></small>
                        </div>
                        <div class="col-md-1 form-group">
                          <label class="col-blue">L.L.C</label>
                          
                          <input type="text" name="llc" value="<?= $items[0]->llc; ?>" class="form-control form-control-sm" required>
                        </div>
                        <!--Sub Heading -->
                        <div class="col-md-12 form-group m-0">
                          <h6 class="col-deep-purple m-0">Order level</h6>
                          <hr class="bg-dark-gray" />
                        </div>
                        <div class="col-md-2 form-group">
                          <label class="col-blue">Minimum</label>
                          <input type="number" name="min" id="min" value="<?= $items[0]->min; ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2 form-group">
                          <label class="col-blue">Maximum</label>
                          <input type="number" name="max" id="max" value="<?= $items[0]->max; ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2 form-group">
                          <label class="col-blue">Re-Order</label>
                          <input type="number" name="reorder" id="reorder" value="<?= $items[0]->re_order; ?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3 mt-4 form-group">
                          <button type="submit" id="Submit" class="btn btn-success" name="submit">Update</button>
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
