<?php
ob_start();
session_start();
include "config.php";

$invoice_no = $_POST['invoice_no'];
$invoice_date = $_POST['invoice_date'];
$due_date = $_POST['due_date'];
$receipt_no = $_POST['receipt_no'];
$receipt_date = $_POST['receipt_date'];
$supplier_id = $_POST['supplier'];
$grand_total = array_sum($_POST['total']);
$created_at = date('Y-m-d H:i:s');


$purchase_query = "INSERT INTO purchase (supplier, receipt_no, receipt_date, invoice_no, invoice_date, created_at, due_date, grand_total) VALUES ('$supplier_id', '$receipt_no', '$receipt_date', '$invoice_no', '$invoice_date', '$created_at', '$due_date', '$grand_total')";

if (mysqli_query($con, $purchase_query)) {
    $purchase_id = mysqli_insert_id($con);
   foreach ($_POST['itemid'] as $key => $item_id) {
        $selling_price = $_POST['selling_price'][$key];
        $qty = $_POST['qty'][$key];
        $cd_percentage = $_POST['cd_percentage'][$key];
        $cd_amount = $_POST['cd_amount'][$key];
        $tax_percentage = $_POST['tax_percentage'][$key];
        $tax_amount = $_POST['tax_amount'][$key];
        $total = $_POST['total'][$key];

        $item_query = "INSERT INTO purchase_items (purchase_id, item_id, price, quantity, cd_percentage, cd_amount,tax_percentage, tax_amount, total)  VALUES ('$purchase_id', '$item_id', '$selling_price', '$qty', '$cd_percentage', '$cd_amount', '$tax_percentage', '$tax_amount', '$total')";

        mysqli_query($con, $item_query);
   }
}

header("Location: purchase.php");
exit;
?>
