<?php
ob_start();
session_start();
include "config.php";
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
$id = $_GET['id'];
$cyear = $_SESSION['cyear'];

$sale = $con->query("SELECT * FROM `bills` a inner join customer b on a.customer=b.id where a.id=$id")->fetch_object();

$sale_id = $con->query("SELECT * FROM `sales` where sale_no=$sale->sale_no and cyear='$cyear'")->fetch_object()->id;
?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <title>Bill | <?php echo $sale->bill_no; ?></title>
      <style type="text/css">
        body{

             font-family:Verdana, Geneva, sans-serif;
             font-size:14px;

      }
      @media print {
            .break {
                page-break-after: always;
            }
            @page {
                size : A4;
                border: .1rem solid #000;
                margin: 15px 20px;
                border-radius: 15px;
            }
        }
       table {
        border-collapse: collapse;
        width: 100%;
      }

      .table th, .table  td{
        border-top: none;
        border: .1rem solid #000;
        padding: 5px;
        text-align: left !important;
      }

      .break:before, .break:after{  
    display: block!important;
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
     <div style="border: 1px solid #000; border-radius: 15px; margin: 10px 0px;padding: 10px; overflow: hidden;">
         <table >
        <tr>
            <td colspan="2" align="left">
                <img src="assets/img/se-logo.png" alt="Company Logo">
            </td>
            <td></td>
           
        </tr>
        <tr>
            <td colspan="3" align="center">
                <p>SPOT AT ENGINEERS 49, Dharmalingam Street, Vengatesha Colony.<br> Old Bus Stand Back Side, Pollachi, Tamilnadu - 642001</p>
            </td>
        </tr>
        <tr>
            <td width="30%">
                CONTACT No.: 96009 38759
            </td>
            <td width="30%">
                GSTIN: 33DHWPM2568H1ZS
            </td>
            <td width="38%">
                STATE NAME:TAMILNADU-CODE:33
            </td>
        </tr>
    </table>
    </div>
     <div style="border: 1px solid #000; border-radius: 15px; margin: 10px 0px;padding: 10px; overflow: hidden;">
     <table style="width: 100%; border-collapse: collapse;">
      <table>
     <h3>Buyer</h3>
            <tr>
              <td width="50%">Invoice No: <?php echo $sale->bill_no; ?></td>
              <td width="50%">Date: <?php echo $sale->bill_date; ?></td>
            </tr>
            <tr>
                <td width="50%">Customer Name: <?php echo $sale->name; ?></td>
                <td width="50%">Phone No: <?php echo $sale->phone; ?></td>
              </tr>
              
              <tr>
                <td width="50%">Address: <?php echo $sale->address_line_1.",".$sale->address_line_2; ?></td>
                <td width="50%">City: <?php echo $sale->city; ?></td>
              </tr>
              <tr>
                <td width="50%">GST No: <?php echo $sale->gst_no; ?></td>
                <td width="50%"></td>
              </tr>
              
        </tbody>
     </table>
</div>
     <h5 style="text-align: center;">Items Details</h5>
     <table class="table" style="margin: 5px 0px 5px 0px;">
            <tr>
                <th>SI No.</th>
                <th>Goods Description</th>
                <th>HSN/SAC</th>
                <th>Brand</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Discount</th>
                <th>Total</th>
            </tr>
            <?php
            $sale_items = $con->query("SELECT a.*,b.name,b.hsn,b.brand FROM sales_items a inner join items b on a.item_id=b.item_id where a.sale_id=$sale_id");
            foreach($sale_items->fetch_all(MYSQLI_ASSOC) as $i => $item){?>
            <tr>
                <td><?php echo $i+1; ?></td>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['hsn']; ?></td>
                <td><?php echo $item['brand']; ?></td>
                <td><?php echo $item['qty']; ?></td>
                <td><?php echo $item['rate']; ?></td>
                <td><?php echo $item['discount']; ?></td>
                <td><?php echo $item['amount']; ?></td>
           <?php }
            ?>
     </table><br>
     <table>
    
        <tr>
            <td width="70%"></td>
          <td width="22%">Sub Total:</td>
          <td width="8%"><?= number_format($sale->net_total,2) ?></td>
        </tr>
        
        <tr>
        <td width="70%"></td>
          <td width="22%">Tax Total :</td>
          <td width="8%"><?= number_format($sale->tax_total,2) ?></td>
        </tr>

    
        <tr>
          <td width="70%"></td>
          <td width="22%">Total Amount (₹):</td>
          <td width="8%"><b><?= number_format($sale->total,2) ?></b></td>
        </tr>

     </table>
     <div style="border: 1px solid #000; border-radius: 15px; margin: 10px 0px;padding: 10px; overflow: hidden;">
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

    
     <p  style="text-align: center;font-size:14px;font-weight:600;">* Thanks For Choosing Spotatengineers For Your Needs.. Please Come Back Soon..! *</p>
    <!-- <script>
        window.print();
        window.onafterprint = () => {
          location.href = "sales.php";
        }
     </script>-->
</body>

</html>
