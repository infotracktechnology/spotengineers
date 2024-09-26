<?php
ob_start();
session_start();
include "config.php";
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
$cyear = $_SESSION['cyear'];
$query = "SELECT * FROM customer";
$result = mysqli_query($con, $query);
if ($result) {
    while ($row = mysqli_fetch_object($result)) {
        $customers[] = $row;
    }
}

$customer_json = json_encode($customers, JSON_UNESCAPED_UNICODE);

$query = "SELECT * FROM items ORDER BY name ASC";
$result = mysqli_query($con, $query);
if ($result) {
    while ($row = mysqli_fetch_object($result)) {
        $items[] = $row;
    }
}

$sale_items = json_encode($items, JSON_UNESCAPED_UNICODE);

$biil_no = $con->query("SELECT max(bill_no)bill_no FROM sales WHERE cyear = '$cyear'")->fetch_array();
$bill_no = $biil_no['bill_no'] ? $biil_no['bill_no']+1 : 1;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    extract($_POST);
    $sales = mysqli_query($con,"INSERT INTO sales (bill_no,bill_date,bill_type,customer,customer_type,total,tax_total,net_total,cyear) VALUES ('$bill_no','$bill_date','$bill_type','$customer','$customer_type','$grandtotal','$tax_amount','$net_total','$cyear')");
    $id = mysqli_insert_id($con);
    foreach($_POST['item'] as $key => $value){
        $qty = $_POST['qty'][$key];
        $rate = $_POST['rate'][$key];
        $amount = $_POST['total'][$key];
        $discount = $_POST['discount'][$key];
        $sales = mysqli_query($con,"INSERT INTO sales_items(bill_id, item_id, qty, rate, amount, discount) VALUES ('$id', '$value', '$qty', '$rate', '$amount', '$discount')");
    }
    header("Location: sales-print.php?id=$id", true, 303);
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Sales</title>
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
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
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
                                <form method="post" name="myForm" action="sales.php" enctype="multipart/form-data">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="col-deep-purple m-0">Sales</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Bill No</label>
                                                    <input type="number" name="bill_no" class="form-control form-control-sm" value="<?php echo $bill_no; ?>" readonly />
                                                </div>

                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Bill Date</label>
                                                    <input type="date" name="bill_date" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" required />
                                                </div>

                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Bill Type</label>
                                                    <select name="bill_type" class="form-control form-control-sm" required>
                                                        <option value="">Select Bill Type</option>
                                                        <option value="Cash">Cash</option>
                                                        <option value="Credit">Credit</option>
                                                        <option value="UPI">UPI</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Customer <a href="customer-create.php"><i class="fa fa-plus"></i> Add</a></label>
                                                    <select name="customer" id="customer" @change="getCustomer($el.value)"  required>
                                                        <option value="">Select Customer</option>
                                                        <?php foreach ($customers as $row) { ?>
                                                            <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Customer Type</label>
                                                    <input type="text" name="customer_type" x-model="customer_type" class="form-control form-control-sm"  />
                                                </div>
                                                
                                              

                                                <div class="col-md-12 form-group m-0">
                                                    <h6 class="col-deep-purple m-0"></h6>
                                                    <hr class="bg-dark-gray" />
                                                </div>

                                                <div class="col-md-12 form-group mt-0">
                                                    <button type="button" class="btn btn-warning" @click="addItem"><i class="fa fa-plus"></i> Add Item</button>
                                                </div>

                                                <template x-for="item in items">

                                                <div class="col-md-12 row">

                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Brand / Spare Name</label>
                                                    <select name="item[]"  x-model="item.item" @change="getItem(item)" class="items">
                                                        <option value="">Select Parts</option>
                                                        <?php foreach ($items as $row) { ?>
                                                            <option value="<?php echo $row->item_id; ?>"><?php echo $row->brand. '/' . $row->name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>


                                                <div class="col-md-1 form-group">
                                                    <label class="col-blue">HSN</label>
                                                    <input type="text" class="form-control form-control-sm" x-model="item.hsn" name="hsn" readonly />
                                                </div>

                                                <div class="col-md-1 form-group">
                                                    <label class="col-blue">Qty</label>
                                                    <input type="number" x-model="item.qty" name="qty[]" min="1" @input="calculateRate(item)" class="form-control form-control-sm"  required/>
                                                </div>

                                                <div class="col-md-1 form-group">
                                                    <label class="col-blue">Rate</label>
                                                    <input type="text" name="rate[]" x-model="item.rate" @keyup="calculateRate(item)" class="form-control form-control-sm"  />
                                                </div>

                                                <div class="col-md-1 form-group">
                                                    <label class="col-blue">M.R.P</label>
                                                    <input type="text" name="mrp[]" x-model="item.mrp" class="form-control form-control-sm"  readonly/>
                                                </div>

                                                <div class="col-md-1 form-group">
                                                    <label class="col-blue">Dis (%)</label>
                                                    <input type="number" x-model="item.discount" name="discount[]" @keyup="calDiscount(item)" class="form-control form-control-sm" />
                                                </div>
                                               
                                               
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Total</label>
                                                    <input type="text" name="total[]" x-model="item.total" class="form-control form-control-sm" readonly />
                                                </div>


                                                <div class="col-md-1 form-group">
                                                   <button type="button" class="btn btn-danger mt-4" @click="deleteItem"><i class="fa fa-times"></i></button>
                                                </div>
                                                </div>
                                                </template>

                                            
                                                    <div class="col-md-2 form-group">
                                                            <label class="col-blue"> Net Total: </label>
                                                            <span  x-html="net_total.toFixed(2)"></span>
                                                            <input type="hidden" name="net_total" x-model="net_total">
                                                        </div>
                                                        <div class="col-md-2 form-group">
                                                            <label class="col-blue">CGST(9%): </label>
                                                            <span x-html="cgst.toFixed(2)"></span>
                                                        </div>

                                                        <div class="col-md-2 form-group">
                                                            <label class="col-blue">SGST(9%): </label>
                                                            <span x-html="sgst.toFixed(2)"></span>
                                                        </div>

                                                        <div class="col-md-2 form-group ">
                                                            <label class="col-blue">Total Tax: </label>
                                                            <span x-html="tax_amount.toFixed(2)"></span>
                                                            <input type="hidden" name="tax_amount" x-model="tax_amount">
                                                        </div>

                                                        <div class="col-md-2 form-group">
                                                            <label class="col-blue">Grand Total: </label>
                                                            <span  x-html="grandtotal.toFixed(2)"></span>
                                                            <input type="hidden" name="grandtotal" x-model="grandtotal">
                                                        </div>
                                                   

                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-success">Submit</button>
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
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>

    const customer = new TomSelect('#customer', {});

    document.addEventListener('alpine:init', () => {
        Alpine.data('app', () => ({
          grandtotal: 0,
          customer_type : '',
          sgst : 0,
          cgst : 0,
          tax_amount : 0,
          net_total : 0,
          sale_items : JSON.parse('<?php echo $sale_items; ?>'),
          customer : JSON.parse('<?php echo $customer_json; ?>'),
          items:[{item: '',qty:'',rate:'',discount:0}],
          addItem(){
            this.items.push({item: '',qty:'',rate:'',discount:0});
            setTimeout(() => this.initTomSelect(), 0);
          },
          deleteItem(index){
            if(confirm('Are you sure?')){
              this.items.splice(index,1);
            }
        },
          calculateRate(item){
            item.total = item.qty * item.rate;
            this.calculateTotal();
          },
          calDiscount(item){
            let total = item.qty * item.rate;
            let discount = total * item.discount / 100;
            item.total = total - discount;
            this.calculateTotal();
          },
          calculateTotal(){
            let total = 0;
            this.items.forEach(item => {
              total += item.total;
            });
            this.grandtotal = total;
            this.cgst = this.grandtotal * 9 / 100;
            this.sgst = this.grandtotal * 9 / 100;
            this.tax_amount = this.cgst + this.sgst;
            this.net_total = this.grandtotal - this.tax_amount;
          },
          getItem(item){
            let item_id = item.item;
            let sale_item = this.sale_items.find(i => i.item_id == item_id);
            item.hsn = sale_item.hsn;
            item.rate = sale_item.selling_price;
            item.mrp = sale_item.mrp;
          },
          initTomSelect() {
            document.querySelectorAll('.items').forEach((el) => {
            if (el && !el.tomselect && typeof el.value !== 'undefined' && el.value !== null) 
                new TomSelect(el, {});
            });
        },
        getCustomer(value){
            let customer = this.customer.find(c => c.id == value);
            this.customer_type = customer.type;
        },
          init(){
            setTimeout(() => this.initTomSelect(), 0);
            console.log(this.customer);
          },
        }))
    })
</script>

</body>

</html>