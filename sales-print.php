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
$sale = $con->query("SELECT * FROM `sales` a inner join customer b on a.customer=b.id where a.id=$id")->fetch_object();
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

         .break {page-break-after: always;}

         }

       table {
        border-collapse: collapse;
        width: 100%;
      }

      .table th, .table thead th, .table td{
        border-top: none;
        border-bottom: .1rem solid #000;
        padding: 5px 0px 5px 0px;
      }

      .break:before, .break:after{  
    display: block!important;
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
              <td width="40%"></td>
              <!-- <td width="30%"><img src="asset('ecommerce/images/logo.png')" alt="Molla Logo"  style="height: 50px;"></td> -->
              <td width="30%"></td>
              </tr>
              <tr>
              <td width="40%"></td>
              <td width="30%"><h2 style="margin:5px 0px;">Spot At Engineers</h2></td>
              <td width="30%"></td>
              </tr>
              <tr>
              <td width="40%"></td>
              <td width="30%"><span>49, Vengatesha Colony,<br>Old Bus Stand Back Side,<br>Pollachi - 642001.</span></td>
              <td width="30%"></td>
              </tr>

              
        </thead>
     </table>

     <table style="margin: 0px 0px 0px 0px;">
        <tbody>
            <tr>
                <td width="100%"><h5 style="text-align: center;">Tax Invoice</h5></td>   

            </tr>
        </tbody>
     </table>
     <table>
            <tr>
              <td width="50%">Bill No: <?php echo $sale->bill_no; ?></td>
              <td width="50%">Bill Date: <?php echo $sale->bill_date; ?></td>
            </tr>
            <tr>
                <td width="50%">Customer Name: <?php echo $sale->name; ?></td>
                <td width="50%">Phone No: <?php echo $sale->phone; ?></td>
              </tr>
              
              <tr>
                <td width="50%">Address: <?php echo $sale->address_line_1." ".$sale->address_line_2; ?></td>
                <td width="50%">Bill Type: <?php echo $sale->bill_type; ?></td>
              </tr>
              <tr>
                <td width="50%">City: <?php echo $sale->city; ?></td>
                <td width="50%">GST No: <?php echo $sale->gst_no; ?></td>
              </tr>
        </tbody>
     </table>

     <h5 style="text-align: center;">Items Details</h5>
     <table class="table" style="margin: 5px 0px 5px 0px;">
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>HSN</td>
                <td>Brand</td>
                <td>Qty</td>
                <td>Rate</td>
                <td>Discount</td>
                <td>Total</td>
            </tr>
            <?php
            $sale_items = $con->query("SELECT a.*,b.name,b.hsn,b.brand FROM sales_items a inner join items b on a.item_id=b.item_id where a.bill_id=$id");
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

     <table style="margin: 5px 0px 5px 0px;">
      <tr><td><b>Terms & Conditions :- </b></td></tr>
      <tr><td></td></tr>
      <tr><td></td></tr>
      <tr><td></td></tr>
     </table>

    
     <p  style="text-align: center;font-size:14px;font-weight:600;">* Thank You! *</p>
     <script>
        window.print();
        window.onafterprint = () => {
            location.href = "sales.php";
        }
     </script>
</body>

</html>
