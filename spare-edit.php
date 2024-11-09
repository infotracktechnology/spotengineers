<?php
ob_start();
session_start();
include "config.php";
if (!isset($_SESSION['username'])) {
    header("Location:index.php");
    exit;
}

// Include database configuration
include "config.php";

// Check if issue_no is provided in the URL
if (!isset($_GET['id'])) {
    http_response_code(400);  // Bad request if issue_no is missing
    exit;
}

$issue_no = $_GET['id'];

// Fetch the spare issue record based on issue_no
$result = $con->query("SELECT a.*, b.job_no, b.job_date, c.name as customer_name, c.phone as customer_phone
                       FROM spare_issue a
                       JOIN job_entry b ON a.job_id = b.id
                       JOIN customer c ON a.customer_id = c.id
                       WHERE a.issue_no = '$issue_no'");

$spare_issue = $result->fetch_object();

// Check if the record exists
if (!$spare_issue) {
    echo "Record not found.";
    exit;
}

// Fetch related items for the form (like appliances and spares)
$appliances = $con->query("SELECT * FROM customer_appliances WHERE customer_id = '$spare_issue->customer_id'")->fetch_all(MYSQLI_ASSOC);
$items = $con->query("SELECT * FROM items")->fetch_all(MYSQLI_ASSOC);

// Fetch spare issue items based on issue_no
$spare_issue_items_result = $con->query("SELECT sii.*, a.appliance_name, b.name as spare_name, b.brand
                                         FROM spare_issue_item sii
                                         JOIN customer_appliances a ON sii.appliance_id = a.id
                                         JOIN items b ON sii.spare_id = b.item_id
                                         WHERE sii.issue_id = '$spare_issue->id'");

$spare_issue_items = $spare_issue_items_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Spare Return</title>
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
                                <form method="post" action="spareissue-update.php" enctype="multipart/form-data">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4>Spare Return</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-1 form-group">
                                                    <label>Job No</label>
                                                    <input type="text" name="job_no" class="form-control form-control-sm" value="<?php echo $spare_issue->job_no; ?>" readonly />
                                                    <input type="hidden" name="job_id" value="<?php echo $spare_issue->job_id; ?>" />
                                                </div>

                                                <div class="col-md-1 form-group">
                                                    <label>Issue No</label>
                                                    <input type="text" name="issue_no" class="form-control form-control-sm" value="<?php echo $spare_issue->issue_no; ?>" readonly />
                                                </div>

                                                <div class="col-md-2 form-group">
                                                    <label>Issue Date</label>
                                                    <input type="date" name="issue_date" value="<?php echo $spare_issue->issue_date; ?>" class="form-control form-control-sm" required />
                                                </div>

                                                <div class="col-md-3 form-group">
                                                    <label>Customer</label>
                                                    <input type="text" name="customer" value="<?php echo $spare_issue->customer_name; ?>" class="form-control form-control-sm" readonly />
                                                    <input type="hidden" name="customer_id" value="<?php echo $spare_issue->customer_id; ?>" />
                                                </div>

                                                <div class="col-md-2 form-group">
                                                    <label>Phone Number</label>
                                                    <input type="text" name="phone" value="<?php echo $spare_issue->customer_phone; ?>" class="form-control form-control-sm" readonly />
                                                </div>

                                                <!-- Removed Add Item Button -->

                                                <div class="col-md-12 form-group m-0">
                                                <?php foreach ($spare_issue_items as $item): ?>
    <div class="row" id="item-<?php echo $item['id']; ?>">
        <div class="col-md-3 form-group">
            <label>Appliance</label>
            <input type="text" class="form-control form-control-sm" value="<?php echo $item['appliance_name']; ?>" readonly />
            <input type="hidden" name="appliance_id[]" value="<?php echo $item['appliance_id']; ?>" />
        </div>

        <div class="col-md-3 form-group">
            <label>Spare/Brand</label>
            <input type="text" class="form-control form-control-sm" value="<?php echo $item['spare_name'] . ' - ' . $item['brand']; ?>" readonly />
            <input type="hidden" name="spare_id[]" value="<?php echo $item['spare_id']; ?>" />
        </div>

        <div class="col-md-1 form-group">
            <label>Qty</label>
            <input type="number" min="1" name="qty[]" value="<?php echo $item['qty']; ?>" class="form-control form-control-sm qty" required />
        </div>

        <div class="col-md-2 form-group">
            <label>Rate</label>
            <input type="number" min="1" name="rate[]" value="<?php echo $item['rate']; ?>" class="form-control form-control-sm rate" required />
        </div>

        <div class="col-md-2 form-group">
            <label>Total</label>
            <input type="number" name="total[]" value="<?php echo $item['total']; ?>" class="form-control form-control-sm total" readonly />
        </div>

        <div class="col-md-1 mt-4">
            <!-- Trash button -->
            <button type="button" class="btn btn-danger" onclick="deleteItem(<?php echo $item['id']; ?>)">
                <i class="fa fa-trash-alt"></i>
            </button>
        </div>
    </div>
<?php endforeach; ?>

                                                </div>

                                                <div class="col-md-3 form-group">
                                                    <button type="submit" class="btn btn-success">Update</button>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Add event listeners to dynamically update when any input field changes
        $('input').on('input', function() {
            var row = $(this).closest('.row');  // Get the row containing the input that changed
            var qty = row.find('.qty').val();   // Get the quantity value (if exists)
            var rate = row.find('.rate').val(); // Get the rate value (if exists)
            var totalInput = row.find('.total');  // Get the total input field
            
            // Dynamically calculate total when Qty or Rate is changed
            if (qty && rate) {
                totalInput.val(qty * rate);  // Calculate total based on qty and rate
            } else {
                totalInput.val(0);  // If qty or rate is missing, set total to 0
            }
            
            // Send the updated values via AJAX, regardless of which input was changed
            var itemId = row.data('item-id');  // Assuming each row has a data attribute for item ID

            // Prepare data for AJAX request
            var dataToSend = {
                item_id: itemId,
                qty: qty,              // Send updated qty
                rate: rate,            // Send updated rate
                total: totalInput.val()  // Send updated total
            };

            // Add any other input fields you may want to send, e.g., additional fields.
            // Example for additional fields:
            row.find('input').each(function() {
                var fieldName = $(this).attr('name');  // Get the input name attribute
                if (fieldName && fieldName !== 'qty' && fieldName !== 'rate') {
                    dataToSend[fieldName] = $(this).val();  // Add it to the data object if not qty or rate
                }
            });

            // Send AJAX request to update the item data
            $.ajax({
                type: 'POST',
                url: 'update_spare_item.php',  // Your backend PHP script to process the update
                data: dataToSend,  // Send the updated data object
                success: function(response) {
                    console.log('Item updated successfully');
                    // Optionally, you can do something with the server's response, e.g., display a message.
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);  // Log errors to console for debugging
                }
            });
        });
    });
</script>

</script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function deleteItem(itemId) {
        if (confirm('Are you sure you want to delete this item?')) {
            // Send AJAX request to delete item using jQuery
            $.ajax({
                type: 'POST',
                url: 'delete_spare_item.php',
                data: { item_id: itemId },
                success: function(response) {
                    // On success, remove the row from the DOM
                    if (response.trim() === 'Item deleted successfully') {
                        $('#item-' + itemId).remove();
                    } else {
                        alert('Failed to delete item.');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    alert('Error: ' + error);
                }
            });
        }
    }
</script>
<!-- General JS Scripts -->

<script src="assets/js/app.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    

</body>
</html>

