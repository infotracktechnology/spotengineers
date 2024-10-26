<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}
if(!isset($_GET['job_id'])){
    http_response_code(400);
    exit;
}

include "config.php";

$cyear = $_SESSION['cyear'];
$items = $con->query("SELECT * FROM items")->fetch_all(MYSQLI_ASSOC);
$items_json = json_encode($items);

$job = $con->query("SELECT a.*,b.name,b.city,b.gst_no,c.name emp_name,b.id customer_id FROM job_entry a join customer b on a.customer_id=b.id join employee c on a.emp_id=c.id where a.id = $_GET[job_id]")->fetch_object();

$appliances = $con->query("SELECT * FROM customer_appliances WHERE customer_id = $job->customer_id")->fetch_all(MYSQLI_ASSOC);

$job_no = $con->query("SELECT max(job_no)job_no FROM job_entry WHERE cyear = '$cyear'")->fetch_array();
$job_no = $job_no['job_no'] ? $job_no['job_no']+1 : 1;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    header("location:spare-issue.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Spot Engineers | Spare Entry</title>
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
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico" />
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
                            <form method="post" name="myForm" action="spare-issue.php" enctype="multipart/form-data">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="col-deep-purple m-0">Spare Issue</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">

                                        <div class="col-md-1 form-group">
                                                <label class="col-blue">Job No</label>
                                                <input type="text" name="job_no" class="form-control form-control-sm" value="<?php echo $job->job_no; ?>" readonly />
                                                <input type="hidden" name="job_id" value="<?php echo $job->id; ?>"  />
                                            </div>

                                            <div class="col-md-1 form-group">
                                                <label class="col-blue">Issue No</label>
                                                <input type="text" name="issue_no" class="form-control form-control-sm" value="1" readonly />
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label class="col-blue">Issue Date</label>
                                                <input type="date" name="issue_date" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" required />
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label class="col-blue">Customer</label>
                                                <input type="text" name="customer" value="<?php echo $job->name; ?>"  class="form-control form-control-sm" readonly />
                                                <input type="hidden" name="customer_id"  />
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label class="col-blue">City</label>
                                                <input type="text" name="city"  value="<?php echo $job->city; ?>" class="form-control form-control-sm" readonly />
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label class="col-blue">GST No</label>
                                                <input type="text" name="gst_no" value="<?php echo $job->gst_no; ?>" class="form-control form-control-sm" readonly />
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label class="col-blue">Technician</label>
                                                <input type="text" name="technician" value="<?php echo $job->emp_name; ?>" class="form-control form-control-sm" readonly />
                                        
                                            </div>

                                            <div class="col-md-12 form-group">
                                                        <button type="button" class="btn btn-warning" @click="addItem">
                                                            <i class="fa fa-plus"></i> Add Item
                                                        </button>
                                                    </div>

                                   <div class="col-md-12 form-group m-0">
                                    <template x-for="(item, index) in items">
                                                <div class="row">
                                                    <div class="col-md-3 form-group">
                                                        <label class="col-blue">Appliances</label>
                                                        <select x-model="item.appliance_id" name="appliance_id[]" class="form-control form-control-sm" required>
                                                            <option value="">Select Appliances</option>
                                                            <?php foreach ($appliances as $row) { ?>
                                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['appliance_name'].' - '.$row['appliance']; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label class="col-blue">Spare/Brand</label>
                                                        <select x-model="item.spare_id" @change="getRate(item);" name="spare_id[]" class="spare" required>
                                                            <option value="">Select Spare</option>
                                                            <?php foreach ($items as $row) { ?>
                                                            <option value="<?php echo $row['item_id']; ?>"><?php echo $row['name'].' - '.$row['brand']; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                
                                            
                                                    <div class="col-md-1 form-group">
                                                        <label class="col-blue">Qty</label>
                                                        <input type="number" min="1" @input="calculateRate(item)" name="qty[]" x-model="item.qty" class="form-control form-control-sm" required/>
                                                    </div>

                                                    <div class="col-md-2 form-group">
                                                        <label class="col-blue">Rate</label>
                                                        <input type="number" min="1" @input="calculateRate(item)" name="rate[]" x-model="item.rate" class="form-control form-control-sm" required/>
                                                    </div>


                                                    <div class="col-md-2 form-group">
                                                        <label class="col-blue">Total</label>
                                                        <input type="number" name="total[]" x-model="item.total" class="form-control form-control-sm" readonly/>
                                                    </div>

                                                    <div class="col-md-1 mt-4">
                                                        <button type="button" class="btn btn-danger" @click="deleteItem(index)">
                                                            <i class="fa fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>

                                            </div>

                                            <div class="col-md-3 form-group">
                                                <button type="submit"  class="btn btn-success">Submit</button>
                                            </div>
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

    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/bundles/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/bundles/datatables/datatables.min.js"></script>
    <script src="assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
 document.addEventListener('alpine:init', () => {
    Alpine.data('app', () => ({
      items:[{appliance_id:'', spare_id:'', qty:0, rate:'', total:''}],
      spares:JSON.parse('<?php echo $items_json; ?>'),
      addItem() {
        this.items.push({appliance_id:'', spare_id:'', qty:0, rate:'', total:''});
        setTimeout(() => this.initTomSelect(), 0);
      },
      getRate(item) {
        let spare = this.spares.find(s => s.item_id == item.spare_id);
        item.rate = spare.selling_price;
      },
      deleteItem(index) {
        if(confirm('Are you sure you want to delete this item?')){
          this.items.splice(index, 1);
        }
      },
      init() {
        setTimeout(() => this.initTomSelect(), 0);
      },
      calculateRate(item) {
        item.total = item.qty * item.rate;
      },
      initTomSelect() {
        document.querySelectorAll('.spare').forEach((el) => {
            if (el && !el.tomselect && typeof el.value !== 'undefined' && el.value !== null) 
                new TomSelect(el, {});
            });
      },
    }));
});

    </script>    




</body>

</html>