<?php 
ob_start();
session_start();
include "config.php";
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
    $id = $_GET['id'];
    $sql = "UPDATE `sales` SET `bill_type` = 'Cash' WHERE `id` = $id;";
    $result = mysqli_query($con, $sql);
    header("location:sales_report.php");
    exit;
?>