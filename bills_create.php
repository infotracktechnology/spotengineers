<?php
ob_start();
session_start();
include "config.php";
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$cyear = $_SESSION['cyear'];
$value = $_POST['value'];
$bill_no = $_POST['bill_no'];
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if($_POST['search'] == 'Sale') {
        $sale = $con->query("SELECT * FROM `sales` a inner join customer b on a.customer=b.id where a.sale_no=$value and a.cyear = '$cyear'")->fetch_object();
        $bills = $con->query("INSERT INTO `bills`(`bill_no`, `sale_no`, `customer`, `bill_date`, `sale_type`, `tax_total`, `net_total`, `total`, `cyear`) VALUES ('$bill_no', '$value','$sale->customer','$sale->sale_date', '$sale->sale_type', '$sale->tax_total', '$sale->net_total', '$sale->total', '$sale->cyear')");
        $id = $con->insert_id;
        header("Location: sales-print.php?id=$id", true, 303);
        exit;
    }
    else {
        $completed = $con->query("UPDATE `job_entry` SET `status` = 'completed' WHERE `job_no` = $value and cyear = '$cyear'");
        $job = $con->query("SELECT * FROM `job_entry` a where a.job_no=$value and a.cyear = '$cyear'")->fetch_object();
        $bill = $con->query("INSERT INTO `bills`(`bill_no`, `bill_date`, `job_no`, `customer`,`total`, `cyear`,technician) VALUES ('$bill_no',curdate(), '$job->job_no', '$job->customer_id', '$job->grand_total', '$cyear','$job->emp_id')");
        $id = $con->insert_id;
        header("Location: job-print.php?id=$id", true, 303);
        exit;
    }
}

?>