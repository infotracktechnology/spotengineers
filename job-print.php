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
            @page {
                size : A4;
                margin: 15px 20px;
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

         img {
            height: 90px;
            width: 100%;
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
p{
    margin:5px 0px;

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
     <div style="border: 1px solid #000; border-radius: 15px; margin: 10px 0px;padding: 10px 5px; overflow: hidden;">
         <table >
        <tr>
            <td colspan="2" align="left">
                <img src="assets/img/se-logo.png" alt="Company Logo">
            </td>
            <td></td>
           
        </tr>
        <tr>
            <td colspan="3" align="center" >
                <p>SPOT AT ENGINEERS 49, Dharmalingam Street, Vengatesha Colony.<br> Old Bus Stand Back Side, Pollachi, Tamilnadu - 642001</p>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <b>Contact No.: 96009 38759</b>
            </td>
            <td width="35%">
               <b> GSTIN: 33DHWPM2568H1ZS</b>
            </td>
            <td width="35%">
               <b> State Name:Tamilnadu-Code:33</b>
            </td>
        </tr>
    </table>
    </div>
<div style="border: 1px solid #000; border-radius: 15px; margin: 10px 0px;padding: 10px; overflow: hidden;">
<table style="width: 100%; border-collapse: collapse;">
    
        <table>
            <p><b>Buyer</b></p>
            <tr>
            <td width="60%" style="">Customer Name: <?php echo $customer->name; ?></td>
                <td width="40%" style="">Invoice No: <?php echo $bill->bill_no; ?></td>
                
            </tr>
            <tr>
            <td width="60%" style="">Address: <?php echo $customer->address_line_1 . "<br>" . $customer->address_line_2; ?></td>
            <td width="40%" style="">Date: <?php echo $bill->bill_date; ?></td>
            </tr>
            <tr>
            <td width="60%" style="">Phone No: <?php echo $customer->phone; ?></td>
                <td width="40%" style="">City: <?php echo $customer->city; ?></td>
            </tr>
            <tr>
                <td width="60%" style="">GST No: <?php echo $customer->gst_no; ?></td>
                <td width="40%" style="">Technician: <?php echo $employee->name; ?></td>
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
$labour_total_rate = $con->query("SELECT SUM(rate) AS total_rate FROM labour_entry WHERE job_id = '$job->id'")->fetch_object()->total_rate;

$spare_total_amount = $con->query("SELECT SUM(total) AS total_amount FROM spare_issue_item WHERE job_id = '$job->id'")->fetch_object()->total_amount;

$subtotal = $labour_total_rate + $spare_total_amount;

$tax_total = ($subtotal * 0.18);

$total_amount = round($subtotal + $tax_total);
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
        <tr><td><b>Company Bank Details :- </b></td></tr>
        <tr><td>Name : Spot At Engineers</td></tr>
        <tr><td>Account No : 120028536542</td></tr>
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
         location.href = "job-entry.php";
     }
</script>
</body>
</html>
