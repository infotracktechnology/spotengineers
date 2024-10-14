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


$works = "SELECT * FROM work";
$works = $con->query($works)->fetch_all(MYSQLI_ASSOC);

$employees = "SELECT * FROM employee";
$employees = $con->query($employees)->fetch_all(MYSQLI_ASSOC);

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
                    <select name="technician" class="form-control form-control-sm" required>
                        <option value="">Select Technician</option>
                        <?php
                        foreach ($employees as $key => $technician) {
                            echo '<option value="'.$technician['id'].'">'.$technician['name'].'</option>';
                        }
                        ?>
                    </select>
                </div>


                                                <div class="col-md-12 form-group m-0">
                                                    <h6 class="col-deep-purple m-0"></h6>
                                                    <hr class="bg-dark-gray" />
                                                
                  
                    <div class="row">
                   

                <div class="col-md-3 form-group">
                <label class="col-blue">Appliances</label>
                  <select name="appliance" class="form-control form-control-sm"  id="appliance">
                    <option value="">Select Appliances</option>
                    <template x-for="(appliance, index) in appliances">
                    <option x-bind:value="appliance.appliance_id" x-text="appliance.appliance + ' - ' + appliance.brand"></option>
                    </template>
                    
    </select>
</div>

                <div class="col-md-3 form-group">
                    <label class="col-blue">Work</label>
                    <select id="work"  class="form-control form-control-sm" @change="getwork($el.value)">
                        <option value="">Select Work</option>
                        <template x-for="workItem in work" :key="workItem.id">
                            <option :value="workItem.id" x-text="workItem.title"></option>
                        </template>
                    </select> 
                </div>
                <div class="col-md-1 form-group">
    <label class="col-blue">Qty</label>
    <!-- Use x-model to bind the qty input -->
    <input type="number" x-model="qty" class="form-control form-control-sm" required @input="calculateTotal" />
</div>

<div class="col-md-1 form-group">
    <label class="col-blue">Rate</label>
    <!-- Bind the amount with x-model to display the selected work rate -->
    <input type="number" x-model="amount" class="form-control form-control-sm" readonly />
</div>

<div class="col-md-2 form-group">
    <label class="col-blue">Total</label>
    <!-- Dynamically update the total as qty and amount are calculated -->
    <input type="text" x-bind:value="total.toFixed(2)" class="form-control form-control-sm" readonly />
</div>

<div class="ccol-md-1 form-group mt-4">
    <button type="button" class="btn btn-warning " @click="addItem">
        <i class="fa fa-plus"></i>
    </button>
</div>
           
    </div>

    <div class="col-md-12 table-responsive form-group">
    <table class="table table-sm table-striped text-right">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Appliance</th>
                <th>Work</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through items and display them -->
            <template x-for="(item, index) in items" :key="index">
                <tr>
                    <td x-text="index + 1"></td>
                    <td x-text="item.appliance"></td>
                    <td x-text="item.work"></td>
                    <td x-text="item.qty"></td>
                    <td x-text="item.rate"></td>
                    <td x-text="item.total.toFixed(2)"></td>
                    <td>
                        <button @click="removeItem(index)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>
                                                  
                                                    
    <div class="row">
        <div class="col-md-3 form-group">
            <label class="col-blue">Grand Total: </label>
            <input type="hidden" name="grand_total" value="" id="grand_total" />
            <span x-text="grandTotal.toFixed(2)">0.00</span>
        </div>
                   
            
    </div>
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

    document.addEventListener('alpine:init', () => {
    Alpine.data('app', () => ({
        customer: JSON.parse('<?php echo $customer_json; ?>'),
        work: JSON.parse('<?php echo $work_json; ?>'), 
        appliances: [],
        city: '',
        gst_no: '',
        amount: 0,
        items:[],
        grandTotal:0,
        getCustomer(value) {
            this.appliances = [];
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
document.addEventListener('alpine:init', () => {
    Alpine.data('app', () => ({
        customer: JSON.parse('<?php echo $customer_json; ?>'),
        work: JSON.parse('<?php echo $work_json; ?>'),
        appliances: [],
        city: '',
        gst_no: '',
        amount: 0,
        qty: 0,
        total: 0,
        totalPrice: 0,
        grandTotal: 0,
        items: [],

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
            this.calculateTotal();
        },

        calculateTotal() {
            this.total = this.qty * this.amount;
            this.updateGrandTotal();
        },

        addItem() {
            if (this.qty > 0 && this.amount > 0) {
                this.items.push({
                    appliance: document.getElementById('appliance').selectedOptions[0].text,
                    work: document.getElementById('title').selectedOptions[0].text,
                    qty: this.qty,
                    rate: this.amount,
                    total: this.total
                });
                this.calculateTotals();
                this.resetInputs(); 
            } else {
                alert("Please fill in valid Quantity and Rate");
            }
        },

        calculateTotals() {
           
            this.totalPrice = this.items.reduce((sum, item) => sum + item.total, 0);
            this.updateGrandTotal();
        },

        updateGrandTotal() {
            this.grandTotal = this.totalPrice; 
        },

        removeItem(index) {
            this.items.splice(index, 1);
            this.calculateTotals();
        },

        resetInputs() {
            this.qty = 0;
            this.amount = 0;
            this.total = 0;
        }
    }));
});

    </script>    




</body>

</html>