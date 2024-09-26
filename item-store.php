<?php
ob_start();
session_start();
include "config.php";

if(isset($_POST['submit'])) {
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

    $sql = "INSERT INTO `items` (`name`, `brand`, `model`, `location`, `rack_no`, `uom`, `mrp`, `selling_price`, `qty`, `llc`, `min`, `max`, `re_order`, `hsn`) VALUES ('$name', '$brand', '$model', '$location', '$rack_no', '$uom', '$mrp', '$selling_price', '$qty', '$llc', '$product_min', '$product_max', '$reorder', '$hsn')";

    echo $sql;
    $result = mysqli_query($con, $sql);     
    if($result) {
        header("Location: items.php");
    }else{
        header("Location: item-create.php");
    }
}
?>

