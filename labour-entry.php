<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}
include "config.php";

$cyear = $_SESSION['cyear'];
$customers = "SELECT a.* FROM customer a join customer_appliances b on a.id=b.customer_id group by a.id";
$customers = $con->query($customers)->fetch_all(MYSQLI_ASSOC);
$customer_data = [];
foreach ($customers as $row) {
    $customer_data[$row['id']] = $row;
    $customer_data[$row['id']]['appliances'] = $con->query("SELECT * FROM customer_appliances WHERE customer_id = '$row[id]'")->fetch_all(MYSQLI_ASSOC);
}

$works = [];
$works = "SELECT * FROM work";
$works = $con->query($works)->fetch_all(MYSQLI_ASSOC);

$customer_json = json_encode($customer_data, JSON_UNESCAPED_UNICODE);
$work_json = json_encode($works, JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Labour Entry</title>
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

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script> -->
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
                            <form method="post" name="myForm" action="labour-entry.php" enctype="multipart/form-data">
    <div class="card card-primary">
        <div class="card-header">
            <h4 class="col-deep-purple m-0">Labour Entry</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-1 form-group">
                    <label class="col-blue">job no</label>
                    <input type="text" name="job_no" class="form-control form-control-sm" value="1" readonly />
                </div>
                <div class="col-md-2 form-group">
                    <label class="col-blue">Service Date</label>
                    <input type="date" name="service_date" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" required />
                </div>
                <div class="col-md-3 form-group">
                    <label class="col-blue">Customer</label>
                    <select id="customer" name="customer" @change="getCustomer($el.value) "  required>
                        <option value="">Select Customer</option>
                        <?php
                        foreach ($customers as $key => $customer) {
                            echo '<option value="' . $customer['id'] . '">' . $customer['name'] . '</option>';
                        }
                        ?>
                    </select> 
                </div>

                <div class="col-md-3 form-group">
                    <label class="col-blue">City</label>
                    <input type="text"  name="city" x-model="city" class="form-control form-control-sm" readonly />
                </div>
          
                <div class="col-md-3 form-group">
                    <label class="col-blue">GST No</label>
                    <input type="text" x-model="gst_no" name="gst_no" class="form-control form-control-sm" readonly />
                </div>
                <div class="col-md-3 form-group">
                    <label class="col-blue">Technician</label>
                    <input type="text" value="" name="technician" class="form-control form-control-sm" required />
                </div>


                                                <div class="col-md-12 form-group m-0">
                                                    <h6 class="col-deep-purple m-0"></h6>
                                                    <hr class="bg-dark-gray" />
                                                
                  
                    <div class="row">
                   

                <div class="col-md-3 form-group">
                <label class="col-blue">Appliances</label>
                  <select name="appliance"  id="appliance">
                    <option value="">Select Appliances</option>
                    <template x-for="(appliance, index) in appliances">
                    <option x-bind:value="appliance.appliance_id" x-text="appliance.appliance + ' - ' + appliance.brand"></option>
                    </template>
                    
    </select>
</div>

                <div class="col-md-3 form-group">
                    <label class="col-blue">Work</label>
                    <select id="title" name="title" class="form-control form-control-sm" @change="getwork($el.value)" required>
                        <option value="">Select Work</option>
                        <template x-for="workItem in work" :key="workItem.id">
                            <option :value="workItem.id" x-text="workItem.title"></option>
                        </template>
                    </select> 
                </div>
                <div class="col-md-1 form-group">
                    <label class="col-blue">Qty</label>
                    <input type="number" class="form-control form-control-sm" id="Qty"  required/>
                </div>
                <div class="col-md-1 form-group">
                    <label class="col-blue">Rate</label>
                    <input type="number" name="amount" x-model="amount" class="form-control form-control-sm" readonly />
                </div>
               
                <div class="col-md-2 form-group">
                    <label class="col-blue">Total</label>
                    <input type="text"  id="total" class="form-control form-control-sm" readonly />
                </div>
                <div class="col-md-2 form-group d-flex align-items-end"> 
                 <button type="button" class="btn btn-warning btn-lg px-3 py-2" id="addItemButton">
                  <i class="fa fa-plus"></i>
                </button>
            </div>
           

                                                <div class="col-md-12 table-responsive form-group">
                                                    <table class="table table-sm table-striped text-right" id="itemsTable">
                                                        <thead>
                                                            
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Appliance</th>
                                                                <th>work</th>
                                                                <th>Qty</th>
                                                                <th>Rate</th>
                                                                <th>Total</th>
                                                                <th>Action</th>
                                                            </tr>
                                                           
                                                        </thead>
                                                        <tbody>

                                                        </tbody>

                                                    </table>
                                                  
                                                    <hr>
                                                    <div class="row">
                    <div class="col-md-3 form-group">
                        <label class="col-blue"> Total Price: </label>
                        <input type="hidden" name="total_price" value="" id="total_price" />
                        <span id="mrpTotal">0.00</span>
                    </div>
                   
                    <div class="col-md-3 form-group">
                        <label class="col-blue">Grand Total: </label>
                        <input type="hidden" name="grand_total" value="" id="grand_total" />
                        <span id="overallTotal">0.00</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 form-group">
                                                    <button type="submit" ng-disabled="myForm.$submitted" class="btn btn-success">Submit</button>
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

   const customerSelect = new TomSelect('#customer', {});
   const applianceSelect  = new TomSelect('#appliance', {});

   document.addEventListener('alpine:init', () => {
    Alpine.data('app', () => ({
        customer: JSON.parse('<?php echo $customer_json; ?>'),
        work: JSON.parse('<?php echo $work_json; ?>'), 
        appliances: [],
        city: '',
        gst_no: '',
        amount: 0,

        getCustomer(value) {
            this.city = this.customer[value].city;
            this.gst_no = this.customer[value].gst_no;
            this.appliances = this.customer[value].appliances;
        },

        getwork(value) {
            const selectedWork = this.work.find(w => w.id == value);
            if (selectedWork) {
                this.amount = selectedWork.amount;
            } else {
                this.amount = 0; 
            }
        },
    }));
});



</script>

</body>

</html>