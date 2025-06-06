<?php
ob_start();
session_start();
include "config.php";
if (!isset($_SESSION['username'])) {
  header("location:index.php");
  exit;
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  extract($_POST);
  $customer = mysqli_query($con,"INSERT INTO customer(name,type,phone,address_line_1,address_line_2,city,gst_no) VALUES('$name','$type','$phone','$address_line_1','$address_line_2','$city','$gst_no')");
  $id = mysqli_insert_id($con);
  foreach($_POST['appliance'] as $key => $value){
    $appliance = $_POST['appliance'][$key];
    $brand = $_POST['brand'][$key];
    $appliance_name = $_POST['appliance_name'][$key];
    mysqli_query($con,"INSERT INTO customer_appliances(customer_id,appliance,brand,appliance_name) VALUES('$id','$value','$brand','$appliance_name')");
  }
  header("location:customer.php?id=$id", true, 303);
  exit;
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
      <div class="main-content" x-data="app">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-header">
                    <h4 class="col-deep-purple m-0">Customer</h4>
                  </div>
                  <div class="card-body">
                  <form method="post" id="myForm" action="customer-create.php" enctype="multipart/form-data" x-data="{ customerType: '' }">
                 <div class="row">
                   <div class="col-md-3 form-group">
                     <label class="col-blue">Name</label>
                     <input type="text" name="name" id="name" class="form-control form-control-sm char" required>
                   </div>

                   <div class="col-md-3 form-group">
                     <label class="col-blue">Type</label>
                     <select name="type" id="type" class="form-control form-control-sm" x-model="customerType" required>
                       <option value="">Select Type</option>
                       <option value="B2C">B2C</option>
                       <option value="B2B">B2B</option>
                     </select>
                   </div>
                  


                   <div class="col-md-3 form-group">
                     <label class="col-blue">Phone</label>
                     <input type="text" name="phone" class="form-control form-control-sm" required>
                   </div>

                   <div class="col-md-3 form-group">
                     <label class="col-blue">Address Line 1</label>
                     <input type="text" name="address_line_1" class="form-control form-control-sm" required>
                   </div>

                   <div class="col-md-3 form-group">
                     <label class="col-blue">Address Line 2</label>
                     <input type="text" name="address_line_2" class="form-control form-control-sm">
                   </div>

                   <div class="col-md-3 form-group">
                     <label class="col-blue">City</label>
                     <input type="text" name="city" class="form-control form-control-sm" required>
                   </div>

                   <div class="col-md-3 form-group" x-show="customerType === 'B2B'">
                     <label class="col-blue">GST No</label>
                     <input type="text" name="gst_no" id="gst_no" class="form-control form-control-sm" :required="customerType === 'B2B'">
                   </div>

                   <div class="col-md-12 form-group m-0">
    <h6 class="col-deep-purple m-0"></h6>
    <hr class="bg-dark-gray" />
</div>


<div class="col-md-12 form-group mt-0">
      <button type="button" class="btn btn-warning" @click="addAppliance"><i class="fa fa-plus"></i> Add Item</button>
  </div>

  <div class="col-md-12" id="itemContainer">
    <template x-for="(appliance, index) in appliances">
      <div class="row item-row" id="initialItemRow">
        <div class="col-md-4 form-group">
          <label class="col-blue">Appliance List</label>
          <select name="appliance[]" class="form-control form-control-sm" required>
            <option value="">Select Appliance</option>
            <option value="Air Conditioner">Air Conditioner</option>
            <option value="Deep Freezer">Deep Freezer</option>
            <option value="Refrigerator">Refrigerator</option>
            <option value="Washing Machine">Washing Machine</option>
            <option value="Water Purifier">Water Purifier (RO)</option>
            <option value="Water Heater">Water Heater</option>
            <option value="UPS">UPS</option>
            <option value="Dish">Dish (DTH)</option>
          </select>
        </div>
        <div class="col-md-3 form-group">
          <label class="col-blue">Brand</label>
          <input type="text" x-model="appliance.brand" name="brand[]" class="form-control form-control-sm" required>
        </div>
        <div class="col-md-3 form-group">
          <label class="col-blue">Appliance Name</label>
          <input type="text" x-model="appliance.appliance_name" name="appliance_name[]" class="form-control form-control-sm" required>
        </div>
        <div class="col-md-1 form-group">
          <button type="button" class="btn btn-danger mt-4" @click="removeAppliance(index)" :disabled="appliances.length === 1">
            <i class="fa fa-times"></i>
          </button>
        </div>
      </div>
    </template>
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
document.addEventListener('alpine:init', () => {
  Alpine.data('app', () => ({

    appliances: [{
      id: 1,
      brand: '',
      appliance_name: '',
    }],

    addAppliance() {
      this.appliances.push({
        id: this.appliances.length + 1,
        brand: '',
        appliance_name: '',
      });
    },

    removeAppliance(id) {
      if(confirm ('Are you sure?')){
        this.appliances.splice(id, 1);
      }
    }

  }))
})
</script>

</html>
