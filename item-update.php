<?php
ob_start();
session_start();
include "config.php";
if (isset($_POST['submit'])) {
    include_once 'config.php';
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $location = $_POST['location'];
    $rack_no = $_POST['rack_no'];
    $uom = $_POST['uom'];
    $mrp = $_POST['mrp'];
    $selling_price = $_POST['selling_price'];
    $qty = $_POST['qty'];
    $llc = $_POST['llc'];
    $product_min = $_POST['min'];
    $product_max = $_POST['max'];
    $reorder = $_POST['reorder'];
    $hsn = $_POST['hsn'];

    $id = $_GET['id'];
    $sql = "UPDATE `items` SET `name`='$name',`brand`='$brand',`model`='$model',`location`='$location',`rack_no`='$rack_no',`uom`='$uom',`mrp`='$mrp',`selling_price`='$selling_price',`qty`='$qty',`llc`='$llc',`min`='$product_min',`max`='$product_max',`re_order`='$reorder',`hsn`='$hsn' WHERE `item_id`='$id'";

    $result = mysqli_query($con, $sql);
    header("Location: items.php");
    exit;
}
