<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}
include "config.php";
$id  = $_GET['id'];
$purchases = $con->query("select a.*,b.supplier_name,b.city from purchase a inner join suppliers b on a.supplier=b.supplier_id where a.purchase_id = $id GROUP by a.purchase_id")->fetch_object();
$purchase_items = $con->query("select a.*,b.name,b.hsn,b.brand,(a.quantity)max_qty,b.item_id from purchase_items a inner join items b on a.item_id=b.item_id where a.purchase_id=$id GROUP by a.item_id")->fetch_all(MYSQLI_ASSOC);
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
                                                <div class="col-md-2 form-group">
                                                        <label class="col-blue">Return No</label>
                                                        <input type="number" name="return_no" class="form-control form-control-sm" value="1" readonly />
                                                    </div>


                                                    <div class="col-md-2 form-group">
                                                        <label class="col-blue">Return Date</label>
                                                        <input type="date" name="return_date" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" required />
                                                    </div>

                                                    <div class="col-md-2 form-group">
                                                        <label class="col-blue">Receipt No</label>
                                                        <input type="text" name="receipt_no" id="receipt_no" class="form-control form-control-sm" value="<?= $purchases->receipt_no; ?>" readonly />
                                                    </div>

                                                    <div class="col-md-3 form-group">
                                                        <label class="col-blue">Supplier Name</label>
                                                        <input type="text" id="supplier" name="supplier_name" class="form-control form-control-sm" value="<?= $purchases->supplier_name; ?>" readonly />
                                                    </div>

                                                    <div class="col-md-2 form-group">
                                                        <label class="col-blue">Invoice No</label>
                                                        <input type="text" value="<?= $purchases->invoice_no; ?>" name="invoice_no" class="form-control form-control-sm" required />
                                                    </div>




                                                    <div class="col-md-3 form-group">
                                                        <label class="col-blue">City</label>
                                                        <input type="text" value="<?= $purchases->city; ?>" class="form-control form-control-sm" readonly />
                                                    </div>

                                                   

                                                </div>


                                                <div class="col-md-12 form-group m-0">
                                                    <h6 class="col-deep-purple m-0"></h6>
                                                    <hr class="bg-dark-gray" />
                                                </div>
                                              
                                               <div class="col-md-3 form-group">
                                                    <label class="col-blue">Spare Name</label>
                                                    <select class="form-control form-control-sm select2" id="parts">
                                                        <option value="">Select Parts</option>
                                                        <?php foreach ($purchase_items as $row) { ?>
                                                            <option value="<?php echo $row['item_id']; ?>"><?php echo $row['brand'].'/'. $row['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                               

                                    <div class="col-md-1 form-group">
                                        <label class="col-blue">Quantity</label>
                                        <input type="number" class="form-control form-control-sm" id="quantity" name="unit" required />
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="col-blue">Buy Rate</label>
                                        <input type="number" class="form-control form-control-sm" id="price" name="mrp" />
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
                                                                <th>Product Name</th>
                                                                <th>price</th>
                                                                <th>Qty</th>
                                                                <th>Tax%</th>
                                                                <th>Tax₹</th>
                                                                <th>Total</th>
                                                                <th>Action</th>
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
    let itemCounter = 1;
    let totalPrice = 0;
    let totalTax = 0;

    function clearFields() {
        $('input[name="unit"], input[name="mrp"], input[name="rate"], #quantity, #price, #amount, #taxPercentage, #taxAmount, input[name="total"]').val('');
    }

    function updateCalculations() {
        const quantity = parseFloat($('#quantity').val()) || 0;
        const price = parseFloat($('#price').val()) || 0;
        const amount = quantity * price;
        const taxPercentage = parseFloat($('#taxPercentage').val()) || 0;
        const taxAmount = (amount * taxPercentage) / 100;
        const total = amount + taxAmount;

        $('#amount').val(amount.toFixed(2));
        $('#taxAmount').val(taxAmount.toFixed(2));
        $('input[name="total"]').val(total.toFixed(2));
    }

    function updateOverallTotals() {
        $('#mrpTotal').text(totalPrice.toFixed(2));
        $('#taxTotal').text(totalTax.toFixed(2));
        $('#overallTotal').text((totalPrice + totalTax).toFixed(2));
    }

    $('#receipt_no').on('input', function() {
        const receipt_no = $(this).val();
        if (receipt_no) {
            $.post('get_purchase_details.php', { receipt_no }, function(response) {
                const data = JSON.parse(response);
                if (data.error) {
                    alert(data.error);
                } else {
                    $('#supplier').val(data.supplier_name);
                    $('#address').val(data.address);
                }
            });
        } else {
            $('#supplier, #address').val('');
        }
    });

    $('#parts').change(function() {
        const item_id = $(this).val();
        if (item_id) {
            $.post('get_spare_details.php', { id: item_id }, function(response) {
                $('input[name="unit"]').val(response.unit);
                $('input[name="mrp"]').val(response.mrp);
                $('input[name="rate"]').val(response.rate);
                updateCalculations();
            }, 'json');
        } else {
            clearFields();
        }
    });

    $('#quantity, #price, #taxPercentage').on('input', updateCalculations);

    $('#addItemButton').on('click', function() {
        const productName = $('#parts option:selected').text();
        const price = parseFloat($('#price').val()) || 0;
        const quantity = parseFloat($('#quantity').val()) || 0;
        const taxAmount = parseFloat($('#taxAmount').val()) || 0;
        const total = parseFloat($('input[name="total"]').val()) || 0;

        if (!productName || quantity <= 0 || price <= 0) {
            alert('Please fill out all required fields.');
            return;
        }

        const newRow = `
            <tr>
                <td>${itemCounter++}</td>
                <td>${productName}</td>
                <td>${price.toFixed(2)}</td>
                <td>${quantity}</td>
                <td>${(taxAmount / total * 100).toFixed(2)}%</td>
                <td>${taxAmount.toFixed(2)}</td>
                <td>${total.toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm removeItem">Remove</button></td>
            </tr>`;
        $('#itemsTable tbody').append(newRow);

        totalPrice += (price * quantity);
        totalTax += taxAmount;
        updateOverallTotals();

        // Remove item from the table
        $('.removeItem').last().on('click', function() {
            const row = $(this).closest('tr');
            const rowTotal = parseFloat(row.find('td').eq(6).text());
            const rowTax = parseFloat(row.find('td').eq(5).text());
            totalPrice -= rowTotal;
            totalTax -= rowTax;
            row.remove();
            updateOverallTotals();
        });

        clearFields();
    });

    $('#submitReceipt').on('click', function() {
        const receiptNo = $('#receipt_no').val();
        if (receiptNo) {
            window.location.href = 'get_purchase_details.php?receipt_no=' + receiptNo;
        } else {
            alert('Please enter a receipt number.');
        }
    });
});


    </script>

</body>

</html>