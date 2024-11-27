<?php
ob_start();
session_start();
include "config.php";
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    extract($_POST);
    $sales = $con->query("UPDATE `sales` SET `tax_total`='$tax_amount',`net_total`='$net_total',`total`='$grandtotal' WHERE id=$id");
    $delete_items = $con->query("DELETE FROM sales_items WHERE sale_id=$id");
    foreach($_POST['item'] as $key => $value){
        $qty = $_POST['qty'][$key];
        $rate = $_POST['rate'][$key];
        $amount = $_POST['total'][$key];
        $discount = $_POST['discount'][$key];
        $items = mysqli_query($con,"INSERT INTO sales_items(sale_id, item_id, qty, rate, amount, discount) VALUES ('$id', '$value', '$qty', '$rate', '$amount', '$discount')");
    }
    header("Location: bills.php?id=$id", true, 303);
    exit;
}
?>
