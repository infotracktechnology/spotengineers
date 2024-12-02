<?php
ob_start();
session_start();
include "config.php";
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
$id = $_GET['id'];
if(!isset($id)) {
    header("Location: sales.php");
    exit;
}
$cyear = $_SESSION['cyear'];
$sale = $con->query("SELECT a.*,b.name,b.type,b.gst_no FROM `sales` a inner join customer b on a.customer=b.id where a.id=$id")->fetch_object();
$items = $con->query("SELECT a.sale_id,a.item_id,a.qty,a.rate,a.amount,a.discount,b.name,b.hsn,b.brand,b.mrp FROM sales_items a inner join items b on a.item_id=b.item_id where a.sale_id=$id");
$item_json = json_encode($items->fetch_all(MYSQLI_ASSOC),JSON_UNESCAPED_UNICODE);

$sale_items = $con->query("SELECT * FROM items")->fetch_all(MYSQLI_ASSOC);
$sale_items_json = json_encode($sale_items,JSON_UNESCAPED_UNICODE);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Sales Return</title>
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
                                <form method="post" name="myForm" action="sales_update.php" enctype="multipart/form-data">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="col-deep-purple m-0">Sales Edit</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Sale No</label>
                                                    <input type="number" name="bill_no" class="form-control form-control-sm" value="<?php echo $sale->sale_no; ?>" readonly />
                                                    <input type="hidden" name="id" value="<?= $sale->id ?>" />
                                                </div>

                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Sale Date</label>
                                                    <input type="date" name="bill_date" value="<?= $sale->sale_date ?>" class="form-control form-control-sm" readonly />
                                                </div>

                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Sale Type</label>
                                                    <input type="text" name="customer_type" value="<?= $sale->sale_type ?>"  class="form-control form-control-sm"  readonly/>
                                                </div>

                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Customer</label>
                                                    <input type="text" name="customer_type" value="<?= $sale->name ?>" class="form-control form-control-sm"  readonly/>
                                                    
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Customer Type</label>
                                                    <input type="text" name="customer_type" value="<?= $sale->type ?>" class="form-control form-control-sm"  readonly/>
                                                </div>

                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">GST No</label>
                                                    <input type="text" name="gst" value="<?= $sale->gst_no ?>" class="form-control form-control-sm"  readonly/>
                                                </div>
                                                
                                              

                                                <div class="col-md-12 form-group m-0">
                                                    <h6 class="col-deep-purple m-0"></h6>
                                                    <hr class="bg-dark-gray" />
                                                </div>

                                                <div class="col-md-12 form-group mt-0">
                                                    <button type="button" class="btn btn-warning" @click="addItem"><i class="fa fa-plus"></i> Add Item</button>
                                                </div>

                                                <template x-for="(item, index) in items">

                                                <div class="col-md-12 row">

                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Brand / Spare Name</label>
                                                    <select name="item[]"  x-model="item.item_id" @change="getItem(item)" class="items" required>
                                                        <option value="">Select Parts</option>
                                                        <?php foreach ($sale_items as $row) { ?>
                                                            <option value="<?php echo $row['item_id']; ?>"><?php echo $row['brand'].'/'.$row['name']; ?></option>
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
                                                    <input type="text" name="total[]" x-model="item.amount" class="form-control form-control-sm" readonly />
                                                </div>


                                                <div class="col-md-1 form-group">
                                                   <button type="button" class="btn btn-danger mt-4" @click="deleteItem(index)"><i class="fa fa-times"></i></button>
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


    document.addEventListener('alpine:init', () => {
        Alpine.data('app', () => ({
          grandtotal: 0,
          sgst : 0,
          cgst : 0,
          tax_amount : 0,
          net_total : 0,
          sale_items : JSON.parse('<?php echo $sale_items_json; ?>'),
          items:JSON.parse('<?php echo $item_json; ?>'),
          addItem(){
            this.items.push({item_id: '',qty:'',rate:'',discount:0,mrp:'',amount:''});
            setTimeout(() => this.initTomSelect(), 0);
          },
          deleteItem(index){
            if(confirm('Are you sure?')){
              this.items.splice(index,1);
            }
            this.calculateTotal();
        },
          calculateRate(item){
            item.amount = Number(item.qty) * Number(item.rate);
            this.calculateTotal();
          },
          calDiscount(item){
            let total = Number(item.qty) * Number(item.rate);
            let discount = total * Number(item.discount) / 100;
            item.amount = total - discount;
            this.calculateTotal();
          },
          calculateTotal(){
            let total = 0;
            this.items.forEach(item => {
              total += Number(item.amount);
            });
            this.grandtotal = total;
            this.cgst = this.grandtotal * 9 / 100;
            this.sgst = this.grandtotal * 9 / 100;
            this.tax_amount = this.cgst + this.sgst;
            this.net_total = this.grandtotal - this.tax_amount;
          },
          getItem(item){
            let item_id = item.item_id;
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
          init(){
            setTimeout(() => this.initTomSelect(), 0);
            console.log(this.items);
            this.calculateTotal();
          },
        }))
    })
</script>

</body>

</html>