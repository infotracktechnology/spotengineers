<?php
ob_start();
session_start();
include_once 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $type = $_POST['type'];
    $phone = $_POST['phone'];
    $address_line_1 = $_POST['address_line_1'];
    $address_line_2 = $_POST['address_line_2'];
    $city = $_POST['city'];
    $gst_no = $_POST['gst_no'] ?? '';
    $id = $_POST['id'];

    $sql = "UPDATE `customer` SET `name` = '$name', `type` = '$type', `phone` = '$phone', `address_line_1` = '$address_line_1', `address_line_2` = '$address_line_2', `city` = '$city', `gst_no` = '$gst_no' WHERE `id` = '$id';";
    $result = mysqli_query($con, $sql);

    $delete_items = mysqli_query($con,"DELETE FROM customer_appliances WHERE customer_id = $id");

    foreach($_POST['appliance'] as $key => $value){
        $appliance = $_POST['appliance'][$key];
        $brand = $_POST['brand'][$key];
        $appliance_name = $_POST['appliance_name'][$key];
        mysqli_query($con,"INSERT INTO customer_appliances(customer_id,appliance,brand,appliance_name) VALUES('$id','$value','$brand','$appliance_name')");
      }

    header("location:customer.php");
    exit;
    
}

?>