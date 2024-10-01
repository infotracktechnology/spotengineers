<?php
ob_start();
session_start();
include "config.php"; 

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

$return_no = $_POST['return_no'];
$return_date = $_POST['return_date'];
$supplier_id = $_POST['supplier_id'];
$receipt_no = $_POST['receipt_no'];
$total_price = isset($_POST['total_price']) ? floatval($_POST['total_price']) : 0;
$total_tax = isset($_POST['total_tax']) ? floatval($_POST['total_tax']) : 0;
$grand_total = isset($_POST['grand_total']) ? floatval($_POST['grand_total']) : 0;
$created_at = date('Y-m-d H:i:s');
echo "Total Price: " . $total_price . "<br>";
echo "Total Tax: " . $total_tax . "<br>";
echo "Grand Total: " . $grand_total . "<br>";

$purchase_query = "INSERT INTO purchase_returns (return_no, return_date, supplier_id, receipt_no, total_price, total_tax, grand_total) 
VALUES ('$return_no', '$return_date', '$supplier_id', '$receipt_no', '$total_price', '$total_tax', '$grand_total')";

if (mysqli_query($con, $purchase_query)) {
    $purchase_returns_items_id = mysqli_insert_id($con); 

    foreach ($_POST['item_id'] as $key => $item_id) {
        $item_id = $_POST['item_id'][$key];
        $rate = $_POST['rate'][$key];
        $qty = $_POST['qty'][$key];
        $tax_percentage = $_POST['tax_percentage'][$key];
        $tax_amount = $_POST['tax_amount'][$key];
        $total = $_POST['total'][$key];

        $purchase_returns_items = "INSERT INTO `purchase_returns_items`(`return_no`, `item_id`, `rate`, `qty`, `tax_percentage`, `tax_amount`, `total`) 
        VALUES ('$return_no', '$item_id', '$rate', '$qty', '$tax_percentage', '$tax_amount', '$total')";

        mysqli_query($con, $purchase_returns_items);
    }
}

header("location:purchases.php");
exit;
?>
