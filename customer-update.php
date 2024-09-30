<?php
ob_start();
session_start();
include_once 'config.php';

if(isset($_POST['submit'])) {

    $name = $_POST['name'];
    $type = $_POST['type'];
    $phone = $_POST['phone'];
    $address_line_1 = $_POST['address_line_1'];
    $address_line_2 = $_POST['address_line_2'];
    $city = $_POST['city'];
    $gst_no = $_POST['gst_no'] ?? '';

    $sql = "UPDATE `customer` SET `name` = '$name', `type` = '$type', `phone` = '$phone', `address_line_1` = '$address_line_1', `address_line_2` = '$address_line_2', `city` = '$city', `gst_no` = '$gst_no' WHERE `id` = '$_GET[id]';";

    $result = mysqli_query($con, $sql);
    header("location:customer.php");
    exit;
    
}
else {

    echo "<script>alert('Customer not Updated.');</script>";
    header("location:customer.php");
}
?>