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
$bill = $con->query("SELECT * FROM `bills` WHERE `id` = $id AND `cyear` = '$cyear'")->fetch_object();
$job = $con->query("SELECT * FROM `job_entry` WHERE `job_no` = '$bill->job_no' AND `cyear` = '$cyear'")->fetch_object();
$customer = $con->query("SELECT * FROM `customer` WHERE `id` = '$job->customer_id'")->fetch_object();
$employee = $con->query("SELECT * FROM `employee` WHERE `id` = '$job->emp_id'")->fetch_object();
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

        .table th, .table td {
            border-top: none;
            border: .1rem solid #000;
            padding: 5px;
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

       .image-box {
            border: 1px solid #000;
            border-radius: 15px;
            margin: 1px 0px;
            padding: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column; 
            align-items: flex-start; 
            height: auto; 
        }

        .image-box img {
            max-width: 520px; 
            height: auto;
            width: 100%;
            margin-bottom: 1px; 
            object-fit: contain;
        }

        
        .image-details {
            font-size: 13px;
            display: flex;
            flex-direction: column; 
            align-items: center; 
            text-align: center; 
            margin-bottom: 1px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        .address {
            text-align: center; 
            margin-bottom: 1px;
        }

        
        .contact-gst-state {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 1px;
        }

        .contact,
        .gst,
        .state {
            width: 100%; 
            text-align: center; 
        }

        .contact {
            text-align: left; 
        }

        .gst {
            text-align: center; 
        }

        .state {
            text-align: right; 
        }


    </style>
</head>

<body>
<table style="margin: 5px 0px 5px 0px;background-color:#00008C;color: #fff;">
        <thead>
              <tr>
                <td><h2 style="margin:5px 0px;text-align: center;">Tax Invoice</h2></td>   
            </tr>
        </thead>
     </table>

     <div class="image-box">
    <img src="assets/img/se-logo.png" alt="Company Logo"> 
    <div class="image-details">
        <div class="address">
            <p><bold>SPOT AT ENGINEERS 49, Dharmalingam Street, Vengatesha Colony. Old Bus Stand Back Side, Pollachi, Tamilnadu - 642001</bold></p>
        </div>
      
        <div class="contact-gst-state">
            <div class="contact">
                <p><bold>CONTACT No.: 96009 38759</bold></p>
            </div>
            <div class="gst">
                <p><bold>GSTIN: 33DHWPM2568H1ZS</bold></p>
            </div>
            <div class="state">
                <p><bold>STATE NAME:TAMILNADU-CODE:33</bold></p>
            </div>
        </div>
    </div>
</div>

<div style="border: 1px solid #000; border-radius: 15px; margin: 10px 0px;padding: 10px; overflow: hidden;">
<table style="width: 100%; border-collapse: collapse;">
    
        <table>
            <tr>
                <td width="50%" style="">Invoice No: <?php echo $bill->bill_no; ?></td>
                <td width="50%" style="">Date: <?php echo $bill->bill_date; ?></td>
            </tr>
            <tr>
                <td width="50%" style="">Customer Name: <?php echo $customer->name; ?></td>
                <td width="50%" style="">Phone No: <?php echo $customer->phone; ?></td>
            </tr>
            <tr>
                <td width="50%" style="">Address: <?php echo $customer->address_line_1 . "," . $customer->address_line_2; ?></td>
                <td width="50%" style="">City: <?php echo $customer->city; ?></td>
            </tr>
            <tr>
                <td width="50%" style="">GST No: <?php echo $customer->gst_no; ?></td>
                <td width="50%" style="">Technician: <?php echo $employee->name; ?></td>
            </tr>
        </table>
    </div>
<table class="table" style="margin: 5px 0px 5px 0px;">
    <tr>
        <th>SI No.</th>
        <th>Goods Description</th>
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
        WHERE a.job_id = '$job->id' -- Linking spare_issue_item to job_entry using job_id
    ");
    $spare_item_count = $spare_issue_item->num_rows; // Count the number of spare items

    foreach ($spare_issue_item->fetch_all(MYSQLI_ASSOC) as $i => $item) {
    ?>
        <tr>
            <td><?php echo $i + 1; ?></td>
            <td><?php echo $item['Name']; ?></td>   
            <td><?php echo $item['Brand']; ?></td>  
            <td><?php echo $item['qty']; ?></td>    
            <td><?php echo $item['rate']; ?></td>   
            <td><?php echo $item['total']; ?></td>  
        </tr>
    <?php } ?>

    <?php
    
    $labour_entry = $con->query("
        SELECT a.*, b.title 
        FROM labour_entry a
        INNER JOIN work b ON a.work_id = b.id
        WHERE a.job_id = '$job->id' -- Linking the job_entry using job_id
    ");

    
    foreach ($labour_entry->fetch_all(MYSQLI_ASSOC) as $i => $item) {
    ?>
        <tr>
            <td><?php echo $spare_item_count + $i + 1; ?></td> 
            <td><?php echo $item['title']; ?></td>  
            <td>-</td>  
            <td>-</td>    
            <td>-</td>   
            <td><?php echo $item['rate']; ?></td>  
        </tr>
    <?php } ?>
</table><br>

<?php
$labour_total_rate = $con->query("
    SELECT SUM(rate) AS total_rate
    FROM labour_entry
    WHERE job_id = '$job->id'
")->fetch_object()->total_rate;

$spare_total_amount = $con->query("
    SELECT SUM(total) AS total_amount
    FROM spare_issue_item
    WHERE job_id = '$job->id'
")->fetch_object()->total_amount;

$subtotal = $labour_total_rate + $spare_total_amount;

$tax_total = ($labour_total_rate * 0.18);

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

<div style="border: 1px solid #000; border-radius: 15px; margin: 10px 0px;padding: 5px; overflow: hidden;">
    <table style="margin: 5px 0px 10px 0px;">
        <tr><td style=""><b>Company Bank Details :- </b></td></tr>
        <tr><td style="">Name : Spot At Engineers</td></tr>
        <tr><td style="">Account No : 120028536542</td></tr>
        <tr>
            <td>IFSC Code : CNRBOO001619</td>
            <td style="text-align: right">Authorized Signatory</td>
        </tr>
    </table>
</div>

<p style="text-align: center;font-size:14px;font-weight:600;">* Thanks For Choosing Spotatengineers For Your Needs.. Please Come Back Soon..! *</p>

<script>
    window.print();
    window.onafterprint = () => {
        location.href = "sales.php";
    }
</script>
</body>
</html>
