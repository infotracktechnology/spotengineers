<?php
ob_start();
session_start();
include "config.php";

// Function to fetch data from the database
function fetchData($con, $query) {
    $data = [];
    $result = mysqli_query($con, $query);
    if ($result) {
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

// Fetch Suppliers and Items
$suppliers = fetchData($con, "SELECT * FROM suppliers ORDER BY supplier_name ASC");
$items = fetchData($con, "SELECT * FROM items ORDER BY name ASC");

// Fetch the latest receipt number and calculate the next one
$result = mysqli_query($con, "SELECT MAX(receipt_no) AS receipt_no FROM purchase");
$row = $result ? mysqli_fetch_assoc($result) : null;
$next_receipt_no = $row && $row['receipt_no'] ? $row['receipt_no'] + 1 : 1;

mysqli_close($con);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Purchase Return</title>
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
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script> -->
</head>

<body>
    <div class="loader"></div>
    <div id="app" ng-app="myApp" ng-controller="issueController">
        <div class="main-wrapper main-wrapper-1">
            <?php require('sidebar.php'); ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form method="post" name="myForm" action="purchase-store.php" enctype="multipart/form-data">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="col-deep-purple m-0">Purchase Return</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="row">
                                                    <!-- Receipt Number -->
                                                    <div class="col-md-2 form-group">
                                                    <label class="col-blue">Receipt No</label>
                                                    <input type="number" name="receipt_no" class="form-control form-control-sm" value="<?php echo $next_receipt_no; ?>" readonly />
                                                </div>
                                                    <!-- Supplier Name -->
                                                    <div class="col-md-3 form-group">
                                                        <label class="col-blue">Supplier Name</label>
                                                        <select id="supplier_name" name="supplier" class="form-control form-control-sm select2" required>
                                                            <option value="">Select Supplier</option>
                                                            <?php
                                                            foreach ($suppliers as $key => $supplier) {
                                                                echo '<option value="' . $supplier->supplier_id . '">' . $supplier->supplier_name . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>


                                                    <div class="col-md-2 form-group">
                                                        <label class="col-blue">Return No</label>
                                                        <input type="number" name="return_no" class="form-control form-control-sm" value="<?php echo $next_receipt_no; ?>" readonly />
                                                    </div>


                                                    <div class="col-md-2 form-group">
                                                        <label class="col-blue">Return Date</label>
                                                        <input type="date" name="return_date" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" required />
                                                    </div>


                                                    <div class="col-md-2 form-group">
                                                        <label class="col-blue">Invoice No</label>
                                                        <input type="text" name="invoice_no" class="form-control form-control-sm" required />
                                                    </div>


                                                    <div class="col-md-4 form-group">
                                                        <label class="col-blue">Reason for Return</label>
                                                        <input type="text" name="reason" class="form-control form-control-sm" required />
                                                    </div>


                                                    <div class="col-md-3 form-group">
                                                        <label class="col-blue">Address</label>
                                                        <input type="text" name="address" class="form-control form-control-sm" readonly />
                                                    </div>


                                                    <div class="col-md-4 form-group">
                                                        <label class="col-blue">Payment Method</label>
                                                        <select name="payment_method" class="form-control form-control-sm" required>
                                                            <option value="">Select Payment Method</option>
                                                            <option value="cash">Cash</option>
                                                            <option value="debit">Debit</option>
                                                            <option value="upi">UPI</option>
                                                        </select>
                                                    </div>

                                                </div>


                                                <div class="col-md-12 form-group m-0">
                                                    <h6 class="col-deep-purple m-0"></h6>
                                                    <hr class="bg-dark-gray" />
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Product Name</label>
                                                    <select class="form-control form-control-sm select2" id="parts">
                                                        <option value="">Select Parts</option>
                                                        <?php foreach ($items as $row) { ?>
                                                            <option value="<?php echo $row->item_id; ?>"><?php echo $row->item_id . ' / ' . $row->name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-1 form-group">
    <label class="col-blue">Quantity</label>
    <input type="number" class="form-control form-control-sm" id="quantity" name="unit" required />
</div>
<div class="col-md-2 form-group">
    <label class="col-blue">Price</label>
    <input type="number" class="form-control form-control-sm" id="price" name="mrp" />
</div>
<div class="col-md-2 form-group">
    <label class="col-blue">Amount</label>
    <input type="number" class="form-control form-control-sm" id="amount" name="rate" readonly />
</div>

<div class="col-md-2 form-group">
    <label class="col-blue">Tax%</label>
    <input type="number" min="0" step="any" id="taxPercentage" class="form-control form-control-sm" />
</div>

<div class="col-md-2 form-group">
    <label class="col-blue">Tax₹</label>
    <input type="number" min="0" step="any" id="taxAmount" class="form-control form-control-sm" readonly />
</div>

                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Total</label>
                                                    <input type="text" class="form-control form-control-sm" name="total" readonly />
                                                </div>
                                                <div class="col-md-1 form-group">
                                                    <button type="button" class="btn btn-warning mt-4 btn-lg px-3 py-2" id="addItemButton">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>

                                                <div class="col-md-12 table-responsive form-group">
                                                    <table class="table table-sm table-striped text-right" id="itemsTable">
                                                        <thead>
                                                            
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Part No / Spare Name</th>
                                                                <th>Rate</th>
                                                                <th>Qty</th>
                                                                <th>Tax%</th>
                                                                <th>Tax₹</th>
                                                                <th>Total</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>

                                                    </table><br>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-3 form-group">
                                                            <label class="col-blue"> Total Price: </label>
                                                            <span id="mrpTotal">0.00</span>
                                                        </div>
                                                        <!-- <div class="col-md-3 form-group">
                                                            <label class="col-blue">Total Discount: </label>
                                                            <span id="discountTotal">0.00</span>
                                                        </div> -->

                                                        <div class="col-md-3 form-group ">
                                                            <label class="col-blue">Total Tax: </label>
                                                            <span id="taxTotal">0.00</span>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label class="col-blue">Grand Total: </label>
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



    <script>

$(document).ready(function() {
    // Event listeners for changes in supplier and parts dropdowns
    $('#supplier_name').change(function() {
        var supplier_id = $(this).val();
        if (supplier_id) {
            $.post('get_supplier_details.php', { id: supplier_id }, function(response) {
                $('input[name="address"]').val(response.address1);
                $('input[name="state"]').val(response.state);
                $('input[name="district"]').val(response.district);
                $('input[name="gst"]').val(response.gst);
            }, 'json');
        } else {
            clearSupplierDetails();
        }
    });

    $('#parts').change(function() {
        var item_id = $(this).val();
        if (item_id) {
            $.post('get_spare_details.php', { id: item_id }, function(response) {
                $('input[name="unit"]').val(response.unit);
                $('input[name="mrp"]').val(response.mrp);
                $('input[name="rate"]').val(response.rate);
                updateCalculations();
            }, 'json');
        } else {
            clearPartDetails();
        }
    });

    // Event listeners for input changes to recalculate amounts
    $('#quantity, #price, #taxPercentage').on('input', function() {
        updateCalculations();
    });

    // Function to update calculations for amount, tax, and total
    function updateCalculations() {
        // Calculate amount
        var quantity = parseFloat($('#quantity').val()) || 0;
        var price = parseFloat($('#price').val()) || 0;
        var amount = quantity * price;
        $('#amount').val(amount.toFixed(2));

        // Calculate tax amount
        var taxPercentage = parseFloat($('#taxPercentage').val()) || 0;
        var taxAmount = (amount * taxPercentage) / 100;
        $('#taxAmount').val(taxAmount.toFixed(2));

        // Calculate total amount including tax
        var total = amount + taxAmount;
        $('input[name="total"]').val(total.toFixed(2));
    }

    // Function to clear supplier details
    function clearSupplierDetails() {
        $('input[name="address"]').val('');
        $('input[name="state"]').val('');
        $('input[name="district"]').val('');
        $('input[name="gst"]').val('');
    }

    // Function to clear part details
    function clearPartDetails() {
        $('input[name="unit"]').val('');
        $('input[name="mrp"]').val('');
        $('input[name="rate"]').val('');
        $('#quantity').val('');
        $('#price').val('');
        $('#amount').val('');
        $('#taxPercentage').val('');
        $('#taxAmount').val('');
        $('input[name="total"]').val('');
    }
});



    </script>

</body>

</html>