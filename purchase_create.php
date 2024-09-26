<?php
   include_once 'config.php';
   if (isset($_POST['submit'])) {
    extract($_POST);
    $query = "INSERT INTO `purchase`(`supplier`, `invoice_no`, `invoice_date`, `due_date`, `receipt_no`, `receipt_date`, `grand_total`, `c_year`) values('$supplier','$invoice_no','$invoice_date', '$due_date','$receipt_no','$receipt_date','0','0')";
   }