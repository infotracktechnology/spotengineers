<?php
session_start();

include "config.php";
$suppliers = [];
$query = "SELECT * FROM suppliers ORDER BY supplier_name ASC";
$result = mysqli_query($con, $query);
if ($result) {
    while ($row = mysqli_fetch_object($result)) {
        $suppliers[] = $row;
    }
}

$items = [];
$query = "SELECT * FROM items ORDER BY name ASC";
$result = mysqli_query($con, $query);
if ($result) {
    while ($row = mysqli_fetch_object($result)) {
        $items[] = $row;
    }
}
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
    <title>Purchase</title>
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
                                            <h4 class="col-deep-purple m-0">Purchase</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Receipt No</label>
                                                    <input type="number" name="receipt_no" class="form-control form-control-sm" value="<?php echo $next_receipt_no; ?>" readonly />
                                                </div>

                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Receipt Date</label>
                                                    <input type="date" name="receipt_date" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" required />
                                                </div>
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
                                                    <label class="col-blue">Invoice No</label>
                                                    <input type="text" name="invoice_no" class="form-control form-control-sm" required />
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Invoice Date</label>
                                                    <input type="date" name="invoice_date" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" required />
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Due Date</label>
                                                    <input type="date" name="due_date" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" required />
                                                </div>


                                                <div class="col-md-4 form-group">
                                                    <label class="col-blue">Address</label>
                                                    <input type="text" name="address" class="form-control form-control-sm" readonly />
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">State Code / State</label>
                                                    <input type="text" name="state" class="form-control form-control-sm" readonly />
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">District</label>
                                                    <input type="text" name="district" class="form-control form-control-sm" readonly />
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">GSTIN No</label>
                                                    <input type="number" name="gst" class="form-control form-control-sm" readonly />
                                                </div>

                                                <div class="col-md-12 form-group m-0">
                                                    <h6 class="col-deep-purple m-0"></h6>
                                                    <hr class="bg-dark-gray" />
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label class="col-blue">Brand/Spare</label>
                                                    <select class="form-control form-control-sm select2" id="parts">
                                                        <option value="">Select Parts</option>
                                                        <?php foreach ($items as $row) { ?>
                                                            <option value="<?php echo $row->item_id; ?>"><?php echo $row->item_id.'-'.$row->name.'/'.$row->brand; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-1 form-group">
                                                    <label class="col-blue">UOM</label>
                                                    <input type="text" class="form-control form-control-sm" name="unit" readonly />
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">MRP</label>
                                                    <input type="text" class="form-control form-control-sm" name="mrp" />
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Rate</label>
                                                    <input type="text" name="rate" class="form-control form-control-sm" />
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Qty</label>
                                                    <input type="number" min="0" step="any" id="qty" class="form-control form-control-sm"  />
                                                </div>
                                                <!-- <div class="col-md-1 form-group">
                                                    <label class="col-blue">SD %</label>
                                                    <input type="number" min="0" step="any" id="sd_percentage" class="form-control form-control-sm" />
                                                </div>
                                                <div class="col-md-1 form-group">
                                                    <label class="col-blue">SD ₹</label>
                                                    <input type="number" step="any" id="sd_amount" class="form-control form-control-sm" />
                                                </div> -->
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">CD %</label>
                                                    <input type="number" min="0" step="any" id="cd_percentage" class="form-control form-control-sm" />
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">CD ₹</label>
                                                    <input type="number" step="any" id="cd_amount" class="form-control form-control-sm" />
                                                </div>

                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Tax %</label>
                                                    <input type="number" min="0" step="any" id="tax_percentage" class="form-control form-control-sm" />
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Tax₹</label>
                                                    <input type="number" step="any" id="tax_amount" class="form-control form-control-sm" />
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label class="col-blue">Total</label>
                                                    <input type="text" class="form-control form-control-sm" name="total" readonly />
                                                </div>
                                                <div class="col-md-1 form-group">
                                                    <button type="button" class="btn btn-warning mt-4" id="addItemButton"><i class="fa fa-plus"></i></button>
                                                </div>

                                                <div class="col-md-12 table-responsive form-group">
                                                    <table class="table table-sm table-striped text-right" id="itemsTable">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Part No / Spare Name</th>
                                                                <th>UOM</th>
                                                                <th>MRP</th>
                                                                <th>Rate</th>
                                                                <th>Qty</th>
                                                                <th>CD%</th>
                                                                <th>CD₹</th>
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
                                                        <div class="col-md-3 form-group">
                                                            <label class="col-blue">Total Discount: </label>
                                                            <span id="discountTotal">0.00</span>
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
            var baseTotal = 0;
            var itemCount = 0;

          
            $('#supplier_name').change(function() {
                var supplier_id = $(this).val();
                if (supplier_id) {
                    $.post('get_supplier_details.php', {
                        id: supplier_id
                    }, function(response) {
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
                    $.post('get_spare_details.php', {
                        id: item_id
                    }, function(response) {
                        $('input[name="unit"]').val(response.unit);
                        $('input[name="mrp"]').val(response.mrp);
                        $('input[name="rate"]').val(response.rate);
                        updateBaseTotal();
                    }, 'json');
                } else {
                    clearPartDetails();
                }
            });

        
            $('#qty, #rate, #cd_percentage, #tax_percentage').on('input', function() {
                updateBaseTotal();
            });

            $('#addItemButton').click(function() {
  
    var partNo = $('#parts').val();
    var uom = $('input[name="unit"]').val();
    var mrp = $('input[name="mrp"]').val();
    var sellingPrice = $('input[name="rate"]').val();
    var qty = $('#qty').val();
    var cdPercentage = $('#cd_percentage').val();
    var cdAmount = $('#cd_amount').val();
    var taxPercentage = $('#tax_percentage').val();
    var taxAmount = $('#tax_amount').val();
    var total = $('input[name="total"]').val();


    if (!partNo || !uom || !mrp || !sellingPrice || !qty || !total) {
        alert('Please fill in all the required fields before adding the item.');
        return; 
    }

   
    itemCount++;
    var row = `<tr>
        <td>${itemCount}</td>
        <td>${partNo} <input type="hidden" name="itemid[]" value="${partNo}" /></td>
        <td>${uom}</td>
        <td>${mrp}<input type="hidden" name="mrp[]" value="${mrp}" /></td>
        <td>${sellingPrice} <input type="hidden" name="selling_price[]" value="${sellingPrice}" /></td>
        <td>${qty} <input type="hidden" name="qty[]" value="${qty}" /></td>
        <td>${cdPercentage} <input type="hidden" name="cd_percentage[]" value="${cdPercentage}" /></td>
        <td>${cdAmount} <input type="hidden" name="cd_amount[]" value="${cdAmount}" /></td>
        <td>${taxPercentage} <input type="hidden" name="tax_percentage[]" value="${taxPercentage}" /></td>
        <td>${taxAmount} <input type="hidden" name="tax_amount[]" value="${taxAmount}" /></td>
        <td>${total} <input type="hidden" name="total[]" value="${total}" /></td>
        <td><button type="button" class="btn btn-danger" onclick="removeItem(this);"><i class="fa fa-trash"></i></button></td>
    </tr>`;

    $('#itemsTable tbody').append(row);
    updateOverallTotal();
    clearForm();
});



            // Function to remove item
            window.removeItem = function(button) {
                $(button).closest('tr').remove();
                updateOverallTotal();
            };

            // Calculate base total and update total
            function updateBaseTotal() {
                var qty = parseFloat($('#qty').val()) || 0;
                var rate = parseFloat($('input[name="rate"]').val()) || 0;
                baseTotal = qty * rate;
                updateTotal();
            }

            function updateTotal() {
    var total = baseTotal;

    // Calculate and subtract the cash discount
    var cdPercentage = parseFloat($('#cd_percentage').val()) || 0;
    if (cdPercentage) {
        var cdAmount = (total * cdPercentage) / 100;
        $('#cd_amount').val(cdAmount.toFixed(2));
        total -= cdAmount;
    }

    // Calculate and add the tax
    var taxPercentage = parseFloat($('#tax_percentage').val()) || 0;
    if (taxPercentage) {
        var taxAmount = (total * taxPercentage) / 100;
        $('#tax_amount').val(taxAmount.toFixed(2));
        total += taxAmount;
    }

    // Update the final total amount
    $('input[name="total"]').val(total.toFixed(2));
}

            // Update overall totals
            function updateOverallTotal() {
                var overallTotal = 0;
                var taxTotal = 0;
                var mrpTotal = 0;
                var discountTotal = 0;

                $('#itemsTable tbody tr').each(function() {
                    var mrp = parseFloat($(this).find('input[name="mrp[]"]').val()) || 0;
                    var qty = parseFloat($(this).find('input[name="qty[]"]').val()) || 0;
                    var total = parseFloat($(this).find('input[name="total[]"]').val()) || 0;
                    var taxAmount = parseFloat($(this).find('input[name="tax_amount[]"]').val()) || 0;
                    var cdAmount = parseFloat($(this).find('input[name="cd_amount[]"]').val()) || 0;

                    mrpTotal += (mrp * qty);
                    discountTotal += cdAmount;
                    overallTotal += total;
                    taxTotal += taxAmount;
                });

                $('#mrpTotal').text(mrpTotal.toFixed(2));
                $('#discountTotal').text(discountTotal.toFixed(2));
                $('#taxTotal').text(taxTotal.toFixed(2));
                $('#overallTotal').text(overallTotal.toFixed(2));
            }




            // Clear form fields
            function clearForm() {
                $('#parts').val('').trigger('change');
                clearPartDetails();
            }

            // Clear supplier details
            function clearSupplierDetails() {
                $('input[name="address"]').val('');
                $('input[name="state"]').val('');
                $('input[name="district"]').val('');
                $('input[name="gst"]').val('');
            }

            // Clear part details
            function clearPartDetails() {
                $('input[name="unit"]').val('');
                $('input[name="mrp"]').val('');
                $('input[name="rate"]').val('');
                $('input[name="total"]').val('');
            }
            // Function to clear the form fields
            function clearForm() {
                $('#parts').val('').trigger('change');
                $('input[name="unit"]').val('');
                $('input[name="mrp"]').val('');
                $('input[name="rate"]').val('');
                $('#qty').val('');
                $('#cd_percentage').val('');
                $('#cd_amount').val('');
                $('#tax_percentage').val('');
                $('#tax_amount').val('');
                $('input[name="total"]').val('');
                updateBaseTotal(); // Reset base total to zero
            }

            // Remove item from the table
            window.removeItem = function(button) {
                $(button).closest('tr').remove();
                updateOverallTotal();
            };


            // Send data to the server
            window.sendData = function() {
                var data = {
                    item_name: $('input[name="item_name[]"]').map(function() {
                        return $(this).val();
                    }).get(),
                    selling_price: $('input[name="selling_price[]"]').map(function() {
                        return $(this).val();
                    }).get(),
                    qty: $('input[name="qty[]"]').map(function() {
                        return $(this).val();
                    }).get(),
                    cd_percentage: $('input[name="cd_percentage[]"]').map(function() {
                        return $(this).val();
                    }).get(),
                    cd_amount: $('input[name="cd_amount[]"]').map(function() {
                        return $(this).val();
                    }).get(),
                    tax_amount: $('input[name="tax_amount[]"]').map(function() {
                        return $(this).val();
                    }).get(),
                    total: $('input[name="total[]"]').map(function() {
                        return $(this).val();
                    }).get(),
                };

                $.post('purchase-store.php', data, function(response) {
                    console.log('Data sent successfully:', response);
                    location.reload(); // Refresh the page after data is successfully sent
                }).fail(function(error) {
                    console.log('Error sending data:', error);
                });
            };
        });
    </script>

</body>

</html>