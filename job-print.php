<?php
ob_start();
session_start();
include "config.php";
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$cyear = $_SESSION['cyear'];

// Fetching the bill details using the provided bill ID and year.
$bill = $con->query("SELECT * FROM `bills` WHERE `id` = $id AND `cyear` = '$cyear'")->fetch_object();

// Fetching job details based on the job number related to the bill.
$job = $con->query("SELECT * FROM `job_entry` WHERE `job_no` = '$bill->job_no' AND `cyear` = '$cyear'")->fetch_object();

// Fetching customer details based on the job entry.
$customer = $con->query("SELECT * FROM `customer` WHERE `id` = '$job->customer_id'")->fetch_object();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Job Bill | <?php echo $bill->bill_no; ?></title>
    <style type="text/css">
        body {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 14px;
        }

        @media print {
            .break {
                page-break-after: always;
            }
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table thead th {
            border-top: none;
            border-bottom: .1rem solid #000;
            padding: 5px 0px 5px 0px;
            text-align: left !important;
        }

        .break:before,
        .break:after {
            display: block !important;
        }

        @page {
            size: B3 landscape;
            margin: 6mm;
        }
    </style>
</head>

<body>
    <table style="margin: 5px 0px 0px 0px;">
        <thead>
            <tr>
                <td>
                    <h2 style="margin:5px 0px;text-align: center;">Spot At Engineers</h2>
                </td>
            </tr>
            <tr>
                <td>
                    <p style="margin:0px;text-align: center;">49, Vengatesha Colony, Old Bus Stand Back Side,</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p style="margin:0px;text-align: center;">Pollachi - 642001.</p>
                </td>
            </tr>
            <tr>
                <td>
                    <h5 style="margin:5px 0px;text-align: center;">Tax Invoice</h5>
                </td>
            </tr>
            
        </thead>
    </table>

    <table>
        <tr>
            <td width="50%">Bill No: <?php echo $bill->bill_no; ?></td>
            <td width="50%">Bill Date: <?php echo $bill->bill_date; ?></td>
        </tr>
        <tr>
            <td width="50%">Customer Name: <?php echo $customer->name; ?></td>
            <td width="50%">Phone No: <?php echo $customer->phone; ?></td>
        </tr>

        <tr>
            <td width="50%">Address: <?php echo $customer->address_line_1 . "," . $customer->address_line_2; ?></td>
            <td width="50%">City: <?php echo $customer->city; ?></td>
            
        </tr>
        <tr>
            <td width="50%">GST No: <?php echo $customer->gst_no; ?></td>
            <td width="50%">Technician: <?php echo $customer->name; ?></td>
        </tr>

        
    </table>

    <h5 style="text-align: center;">Labour Details</h5>
    <table class="table" style="margin: 5px 0px 5px 0px;">
    <tr>
        <th>#</th>
        <th>Work</th>
        <th>Rate</th>
    </tr>
    <?php
    // Fetching labour entry details for the job by linking job_id in labour_entry to job_entry
    // and fetching the work title from the work table.
    $labour_entry = $con->query("
        SELECT a.*, b.title 
        FROM labour_entry a
        INNER JOIN work b ON a.work_id = b.id
        WHERE a.job_id = '$job->id'  -- Linking the job_entry using job_id
    ");

    // Loop through and display each entry from the labour_entry table
    foreach ($labour_entry->fetch_all(MYSQLI_ASSOC) as $i => $item) {
    ?>
        <tr>
            <td><?php echo $i + 1; ?></td>
            <td><?php echo $item['title']; ?></td>  <!-- Display work title from work table -->
            <td><?php echo $item['rate']; ?></td>   <!-- Display rate from labour_entry table -->
        </tr>
    <?php } ?>
</table><br>
<h5 style="text-align: center;">Spare Details</h5>
<table class="table" style="margin: 5px 0px 5px 0px;">
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Brand</th>
        <th>Qty</th>
        <th>Rate</th>
        <th>Total</th>
    </tr>
    <?php
$spare_issue_item = $con->query("
    SELECT a.*, b.name AS Name, b.brand AS Brand
    FROM spare_issue_item a
    INNER JOIN items b ON a.spare_id = b.item_id
    WHERE a.job_id = '$job->id'  -- Linking spare_issue_item to job_entry using job_id
");

foreach ($spare_issue_item->fetch_all(MYSQLI_ASSOC) as $i => $item) {
?>
    <tr>
        <td><?php echo $i + 1; ?></td>
        <td><?php echo $item['Name']; ?></td>   <!-- Displaying the item name from items table -->
        <td><?php echo $item['Brand']; ?></td>  <!-- Displaying the item brand from items table -->
        <td><?php echo $item['qty']; ?></td>    <!-- Displaying quantity from spare_issue_item table -->
        <td><?php echo $item['rate']; ?></td>   <!-- Displaying rate from spare_issue_item table -->
        <td><?php echo $item['total']; ?></td>  <!-- Displaying total from spare_issue_item table -->
    </tr>
<?php } ?>
</table><br>




<?php
// Calculate the sum of rates from the labour details
$labour_total_rate = $con->query("
    SELECT SUM(rate) AS total_rate
    FROM labour_entry
    WHERE job_id = '$job->id'
")->fetch_object()->total_rate;

// Calculate the sum of totals from the spare details
$spare_total_amount = $con->query("
    SELECT SUM(total) AS total_amount
    FROM spare_issue_item
    WHERE job_id = '$job->id'
")->fetch_object()->total_amount;

// Calculate Subtotal (sum of labour total rate + spare total amount)
$subtotal = $labour_total_rate + $spare_total_amount;

// Calculate Tax Total (18% GST on the labour total rate)
$tax_total = ($labour_total_rate * 0.18);

// Calculate Total Amount (subtotal + tax total)
$total_amount = round($subtotal + $tax_total, 2);
?>

<table>
    <tr>
        <td width="70%"></td>
        <td width="22%">Sub Total:</td>
        <td width="8%"><?= number_format($subtotal, 2) ?></td>
    </tr>

    <tr>
        <td width="70%"></td>
        <td width="22%">Tax Total:</td>
        <td width="8%"><?= number_format($tax_total, 2) ?></td>
    </tr>

    <tr>
        <td width="70%"></td>
        <td width="22%">Total Amount (₹):</td>
        <td width="8%"><b><?= number_format($total_amount, 2) ?></b></td>
    </tr>
</table>

    <table style="margin: 5px 0px 5px 0px;">
        <tr>
            <td><b>Terms & Conditions :- </b></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </table>

    <p style="text-align: center;font-size:14px;font-weight:600;">* Thank You! *</p>

    <script>
        window.print();
        window.onafterprint = () => {
            location.href = "sales.php";
        }
    </script>
</body>

</html>
