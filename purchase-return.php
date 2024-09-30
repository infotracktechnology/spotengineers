<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}
include "config.php";
$id = $_GET['id'];
$purchases = $con->query("SELECT a.*, b.supplier_name, b.city FROM purchase a INNER JOIN suppliers b ON a.supplier = b.supplier_id WHERE a.purchase_id = $id GROUP BY a.purchase_id")->fetch_object();
$purchase_items = $con->query("SELECT a.*, b.name, b.hsn, b.brand, (a.quantity) AS max_qty, b.item_id FROM purchase_items a INNER JOIN items b ON a.item_id = b.item_id WHERE a.purchase_id = $id GROUP BY a.item_id")->fetch_all(MYSQLI_ASSOC);

$purchasedQuantities = [];
foreach ($purchase_items as $row) {
    $purchasedQuantities[$row['item_id']] = $row['max_qty'];
}

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
                            <form method="post" name="myForm" action="purchase-return-store.php" enctype="multipart/form-data">
    <div class="card card-primary">
        <div class="card-header">
            <h4 class="col-deep-purple m-0">Purchase Return</h4>
        </div>
        <div class="card-body">
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
                    <input type="text" id="supplier" class="form-control form-control-sm" value="<?= $purchases->supplier_name; ?>" readonly />
                    <input type="hidden" name="supplier_id" id="supplier_id" value="<?= $purchases->supplier; ?>" />
                </div>
                <div class="col-md-2 form-group">
                    <label class="col-blue">Invoice No</label>
                    <input type="text" value="<?= $purchases->invoice_no; ?>" name="invoice_no" class="form-control form-control-sm" readonly />
                </div>
                <div class="col-md-3 form-group">
                    <label class="col-blue">City</label>
                    <input type="text" value="<?= $purchases->city; ?>" class="form-control form-control-sm" readonly />
                </div>

                                                <div class="col-md-12 form-group m-0">
                                                    <h6 class="col-deep-purple m-0"></h6>
                                                    <hr class="bg-dark-gray" />
                                                </div>
                                              
                                                <div class="row">
                <div class="col-md-3 form-group">
                    <label class="col-blue">Spare Name</label>
                    <select class="form-control form-control-sm select2" id="parts">
                        <option value="">Select Parts</option>
                        <?php foreach ($purchase_items as $row) { ?>
                            <option value="<?php echo $row['item_id']; ?>"><?php echo $row['item_id'].'-'.$row['name'].'/'.$row['brand']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-1 form-group">
                    <label class="col-blue">Quantity</label>
                    <input type="number" class="form-control form-control-sm" id="quantity"  min="1" />
                </div>
                <div class="col-md-2 form-group">
                    <label class="col-blue">Buy Rate</label>
                    <input type="number" class="form-control form-control-sm" id="price" />
                </div>
                <div class="col-md-1 form-group">
                    <label class="col-blue">Tax%</label>
                    <input type="number" min="0" step="any" id="taxPercentage" class="form-control form-control-sm"  />
                </div>
                <div class="col-md-2 form-group">
                    <label class="col-blue">Tax₹</label>
                    <input type="number" min="0" step="any" id="taxAmount" class="form-control form-control-sm" readonly  />
                </div>
                <div class="col-md-2 form-group">
                    <label class="col-blue">Total</label>
                    <input type="text"  id="total" class="form-control form-control-sm" readonly />
                </div>
                <div class="col-md-1 form-group">
                    <button type="button" class="btn btn-warning mt-3 btn-lg px-3 py-2" id="addItemButton">
                        <i class="fa fa-plus small-icon"></i>
                    </button>
                </div>
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

                                                    </table>
                                                  
                                                    <hr>
                                                    <div class="row">
                    <div class="col-md-3 form-group">
                        <label class="col-blue"> Total Price: </label>
                        <input type="hidden" name="total_price" value="" id="total_price" />
                        <span id="mrpTotal">0.00</span>
                    </div>
                    <div class="col-md-3 form-group ">
                        <label class="col-blue">Total Tax: </label>
                        <input type="hidden" name="total_tax" value="" id="total_tax" />
                        <span id="taxTotal">0.00</span>
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
    <script>
      
      $(document).ready(function() {
    let itemCounter = 1;
    let totalPrice = 0;
    let totalTax = 0;
    let purchasedQuantity = <?= json_encode($purchasedQuantities); ?>;
    let addedQuantities = {}; // Track quantities added for each item

    function clearFields() {
        $('input[name="unit"], input[name="mrp"], input[name="rate"], #quantity, #price, #amount, #taxPercentage, #taxAmount, input[name="total"]').val('');
    }

    function updateCalculations() {
        const quantity = parseFloat($('#quantity').val()) || 0;
        const price = parseFloat($('#price').val()) || 0;
        const taxPercentage = parseFloat($('#taxPercentage').val()) || 0;

        const amount = quantity * price;
        const taxAmount = (amount * taxPercentage) / 100;
        const total = amount + taxAmount;

        $('#amount').val(amount.toFixed(2));
        $('#taxAmount').val(taxAmount.toFixed(2));
        $('#total').val(total.toFixed(2));
    }

    function updateOverallTotals() {
        $('#mrpTotal').text(totalPrice.toFixed(2));
        $('#taxTotal').text(totalTax.toFixed(2));
        $('#overallTotal').text((totalPrice + totalTax).toFixed(2));
    }

    function handleRowRemoval(row) {
        const rowTotal = parseFloat(row.find('td').eq(6).text());
        const rowTax = parseFloat(row.find('td').eq(5).text());
        const rowQuantity = parseFloat(row.find('td').eq(3).text());

        totalPrice -= rowTotal;
        totalTax -= rowTax;
        row.remove();

       
        const item_id = $('#parts').val();
        addedQuantities[item_id] -= rowQuantity;

        updateOverallTotals();
    }



    $('#quantity, #price, #taxPercentage').on('input', updateCalculations);

    $('#addItemButton').on('click', function() {
        const productName = $('#parts option:selected').text();
        const price = parseFloat($('#price').val()) || 0;
        const quantity = parseFloat($('#quantity').val()) || 0;
        const taxAmount = parseFloat($('#taxAmount').val()) || 0;
        const total = parseFloat($('input[name="total"]').val()) || 0;
        const item_id = $('#parts').val();

        if (!productName || quantity <= 0 || price <= 0 || !item_id) {
            alert('Please fill out all required fields.');
            return;
        }

       
        addedQuantities[item_id] = addedQuantities[item_id] || 0;

      
        if (addedQuantities[item_id] + quantity > purchasedQuantity[item_id]) {
            alert(`You cannot return more than ${purchasedQuantity[item_id]} items for ${productName}.`);
            return;
        }

        const newRow = `
            <tr>
                <td>${itemCounter++}</td>
                <td>${productName} <input type="hidden" name="item_id[]" value="${item_id}"></td>
                <td>${price.toFixed(2)} <input type="hidden" name="rate[]" value="${price}"></td>
                <td>${quantity} <input type="hidden" name="qty[]" value="${quantity}"></td>
                <td>${((taxAmount / total) * 100).toFixed(2)}% <input type="hidden" name="tax_percentage[]" value="0"></td>
                <td>${taxAmount.toFixed(2)} <input type="hidden" name="tax_amount[]" value="${taxAmount}"></td>
                <td>${total.toFixed(2)} <input type="hidden" name="total[]" value="${total}"></td>
                <td><button class="btn btn-danger btn-sm removeItem"><i class="fa fa-trash"></i></button></td>
            </tr>`;
        $('#itemsTable tbody').append(newRow);

      
        totalPrice += (price * quantity);
        totalTax += taxAmount;
        addedQuantities[item_id] += quantity;
        updateOverallTotals();

     
        $('.removeItem').last().on('click', function() {
            handleRowRemoval($(this).closest('tr'));
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

   
    $('#quantity').on('input', function() {
        const item_id = $('#parts').val();
        const quantity = parseInt($(this).val(), 10) || 0;

        if (item_id && purchasedQuantity[item_id] < quantity) {
            alert(`You cannot return more than ${purchasedQuantity[item_id]} items for ${$('#parts option:selected').text()}.`);
            $(this).val(purchasedQuantity[item_id]); 
        }
    });
});


$('#addItemButton').on('click', function() {
    // existing code ...

    const newRow = `...`; // Your existing newRow definition

    // Add the row to the table
    $('#itemsTable tbody').append(newRow);

    // Collect the added items
    let purchaseItems = [];
    $('#itemsTable tbody tr').each(function() {
        const row = $(this);
        purchaseItems.push({
            item_id: row.find('td').eq(1).text().split('-')[0], // Extract item_id
            rate: parseFloat(row.find('td').eq(2).text()),
            quantity: parseInt(row.find('td').eq(3).text()),
            tax_percentage: parseFloat(row.find('td').eq(4).text().replace('%', '')),
            tax_amount: parseFloat(row.find('td').eq(5).text()),
            total: parseFloat(row.find('td').eq(6).text())
        });
    });

    // Store the purchase items in a hidden input before submitting
    $('input[name="purchase_items"]').val(JSON.stringify(purchaseItems));

    // existing code to clear fields...
});

        // Collect the added items
        let purchaseItems = [];
        $('#itemsTable tbody tr').each(function() {
            const row = $(this);
            purchaseItems.push({
                item_id: row.find('td').eq(1).text().split('-')[0], // Extract item_id
                rate: parseFloat(row.find('td').eq(2).text()),
                quantity: parseInt(row.find('td').eq(3).text()),
                tax_percentage: parseFloat(row.find('td').eq(4).text().replace('%', '')),
                tax_amount: parseFloat(row.find('td').eq(5).text()),
                total: parseFloat(row.find('td').eq(6).text())
            });
        });

        // Store the purchase items in a hidden input
        $('input[name="purchase_items"]').val(JSON.stringify(purchaseItems));
 
        $('#addItemButton').on('click', function() {
    let purchaseItems = [];
    
    $('#itemsTable tbody tr').each(function() {
        const row = $(this);
        purchaseItems.push({
            item_id: row.find('td').eq(1).text().split('-')[0],  // Extract item_id
            rate: parseFloat(row.find('td').eq(2).text()),
            quantity: parseInt(row.find('td').eq(3).text()),
            tax_percentage: parseFloat(row.find('td').eq(4).text().replace('%', '')),
            tax_amount: parseFloat(row.find('td').eq(5).text()),
            total: parseFloat(row.find('td').eq(6).text())
        });
    });

    // Store the purchase items in the hidden input field before form submission
    $('input[name="purchase_items"]').val(JSON.stringify(purchaseItems));
});

    </script>

</body>

</html>