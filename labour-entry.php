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

$job_no = $con->query("SELECT max(job_no)job_no FROM job_entry WHERE cyear = '$cyear'")->fetch_array();
$job_no = $job_no['job_no'] ? $job_no['job_no']+1 : 1;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
 extract($_POST);
 $job = $con->query("INSERT INTO `job_entry`(`job_no`, `job_date`, `customer_id`, `gst_no`, `emp_id`, `cyear`,`grand_total`,`source`) VALUES ('$job_no', '$job_date', '$customer_id', '$gst_no', '$emp_id', '$cyear', '$grand_total', '$source')");
 if($job) {
     $job_id = $con->insert_id;
     if(isset($_POST['work_id'])){
     foreach ($_POST['work_id'] as $key => $work_id) {
         $appliance_id = $_POST['appliance_id'][$key];
         $rate = $_POST['rate'][$key];
        $labour = $con->query("INSERT INTO `labour_entry`(`job_id`, `appliance_id`, `work_id`, `rate`) VALUES ('$job_id', '$appliance_id', '$work_id', '$rate')");
     }
    }
    }
    echo '<script>alert("Labour Entry Added Successfully");</script>';
    echo '<script>window.location.href="labour-entry.php";</script>';
}

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
                            <form method="post" name="myForm" action="labour-entry.php" @summit="submitForm($event)"  enctype="multipart/form-data">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="col-deep-purple m-0">Labour Entry</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-1 form-group">
                                                <label class="col-blue">Job No</label>
                                                <input type="text" name="job_no" class="form-control form-control-sm" value="<?php echo $job_no; ?>" readonly />
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label class="col-blue">Job Date</label>
                                                <input type="date" name="job_date" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" required />
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label class="col-blue">Customer</label>
                                                <select id="customer" name="customer_id" @change="getCustomer($el.value)" required>
                                                    <option value="">Select Customer</option>
                                                    <?php
                                                    foreach ($customers as $key => $customer) {
                                                        echo '<option value="' . $customer['id'] . '">' . $customer['phone'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label class="col-blue">Customer Name</label>
                                                <input type="text" name="customer_name" x-model="customer_name" class="form-control form-control-sm" readonly />
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label class="col-blue">City</label>
                                                <input type="text" name="city" x-model="city" class="form-control form-control-sm" readonly />
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label class="col-blue">GST No</label>
                                                <input type="text" x-model="gst_no" name="gst_no" class="form-control form-control-sm" readonly />
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label class="col-blue">Technician</label>
                                                <select name="emp_id" class="form-control form-control-sm" required>
                                                    <option value="">Select Technician</option>
                                                    <?php
                                                    foreach ($employees as $key => $technician) {
                                                        echo '<option value="' . $technician['id'] . '">' . $technician['name'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-md-3 form-group">
                                                <label class="col-blue">Lead Source</label>
                                                <select id="source" name="source" class="form-control form-control-sm" required>
                                                    <option value="" selected disabled>Select Source</option>
                                                    <option value="followup">Call Followup</option>
                                                    <option value="customer referral">Customer Referral</option>
                                                    <option value="social media">Social Media</option>
                                                    <option value="walkin">Walkin</option>
                                                    <option value="website">Webiste</option>
                                                    <option value="office">Office</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>

                                   <div class="col-md-12 form-group m-0">
                                            
                                                <hr class="bg-dark-gray" />
                                                <div class="row">
                                                    <div class="col-md-3 form-group">
                                                        <label class="col-blue">Appliances</label>
                                                        <select x-model="appliance" class="form-control form-control-sm" id="appliance">
                                                            <option value="">Select Appliances</option>
                                                            <template x-for="(appliance, index) in appliances">
                                                                <option x-bind:value="appliance.id" x-text="appliance.appliance + ' - ' + appliance.brand"></option>
                                                            </template>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label class="col-blue">Work</label>
                                                        <select id="work" x-model="work" class="form-control form-control-sm" @change="getwork()">
                                                            <option value="">Select Work</option>
                                                           <?php 
                                                            foreach ($works as $key => $work) {
                                                                echo "<option value='$work[id]'>$work[title] - $work[category]</option>";
                                                           }
                                                           ?>
                                                        </select>
                                                    </div>
                                                
                                            
                                                    <div class="col-md-2 form-group">
                                                        <label class="col-blue">Rate</label>
                                                        <input type="number" class="form-control form-control-sm" x-model="rate" />
                                                    </div>


                                                   
                                                    <div class="col-md-1 form-group mt-4">
                                                        <button type="button" class="btn btn-warning" @click="addItem">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                             
                                                    <table class="table table-sm table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Appliance</th>
                                                                <th>Work Title</th>
                                                                <th>Work Category</th>
                                                                <th>Rate</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <template x-for="(item, index) in items" :key="index">
                                                                <tr>
                                                                    <td x-text="index + 1"></td>
                                                                    <td x-text="item.appliance"></td>
                                                                    <td x-text="item.work"></td>
                                                                    <td x-text="item.category"></td>
                                                                    <td x-text="item.rate.toFixed(2)"></td>
                                                                    <td><button type="button" @click="removeItem(index)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                                    </td>
                                                                    <input type="hidden" name="rate[]" x-model="item.rate" />
                                                                    <input type="hidden" name="appliance_id[]" x-model="item.appliance_id" />
                                                                    <input type="hidden" name="work_id[]" x-model="item.work_id" />
                                                                </tr>
                                                            </template>
                                                        </tbody>
                                                    </table>
                                                

                                            
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Grand Total: </label>
                                                    <span x-text="grandTotal.toFixed(2)">0.00</span>
                                                    <input type="hidden" name="grand_total" x-model="grandTotal" />
                                                </div>
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
  const customerSelect = new TomSelect('#customer', {});
 document.addEventListener('alpine:init', () => {
    Alpine.data('app', () => ({
        customer: JSON.parse('<?php echo $customer_json; ?>'),
        work_list: JSON.parse('<?php echo $work_json; ?>'),
        appliances: [],
        customer_name: '',
        city: '',
        gst_no: '',
        appliance: '',
        work: '',
        rate: 0,
        items: [],
        grandTotal: 0,
  
        getCustomer(value) {
            this.customer_name = this.customer[value]?.name || '';
            this.city = this.customer[value]?.city || '';
            this.gst_no = this.customer[value]?.gst_no || '';
            this.appliances = this.customer[value]?.appliances || [];
        },

        getwork() {
            const selectedWork = this.work_list.find(w => w.id == this.work);
            this.rate = selectedWork ? parseFloat(selectedWork.amount) : 0;
           
        },

        addItem() {
            if (this.rate <= 0 || !this.appliance || !this.work) {
                alert("Please fill in all the required fields.");
                return false;
            }
            let selectedWork = this.work_list.find(w => w.id == this.work);
            let selecteAppliance = this.appliances.find(a => a.id == this.appliance);
            console.log(this.appliance);
            
            let item = {
                appliance_id: this.appliance,
                appliance: selecteAppliance.appliance,
                work_id: this.work,
                work: selectedWork.title,
                category: selectedWork.category,
                rate: this.rate,
            };

            this.items.push(item);
            this.updateGrandTotal();
            this.resetInputs();
        },

        updateGrandTotal() {
            this.grandTotal = this.items.reduce((sum, item) => sum + Number(item.rate), 0);
            this.grandTotal.toFixed(2);
        },

        removeItem(index) {
            if (confirm("Are you sure you want to delete this item?")) {
                this.items.splice(index, 1);
                this.updateGrandTotal();
            }
        },

        resetInputs() {
            this.appliance = '';
            this.work = '';
            this.rate = 0;
        },
        submitForm(e){
            // e.preventDefault();
            // if (this.items.length == 0) {
            //     alert("Please add at least one item.");
            //     return false;
            // }
            // e.target.submit();
            // return true;
        }
           
    }));
});

    </script>    




</body>

</html>