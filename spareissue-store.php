<?php
ob_start();
session_start();
include "config.php";
$cyear = $_SESSION['cyear'];
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    $issue = $con->query("INSERT INTO `spare_issue`(`job_id`, `issue_no`, `issue_date`, `customer_id`, `cyear`) VALUES ('$job_id', '$issue_no', '$issue_date', '$customer_id', '$cyear')");
    $issue_id = $con->insert_id;
    $job = $con->query("UPDATE `job_entry` SET `status` = 'spare issue' WHERE `id` = '$job_id'");
    foreach($_POST['spare_id'] as $key => $spare_id) {
        $appliance_id = $_POST['appliance_id'][$key]; 
        $qty = $_POST['qty'][$key];
        $rate = $_POST['rate'][$key];
        $total = $_POST['total'][$key];
        $tax = $total - ($total * 0.18);
        $issue_item =    $con->query("INSERT INTO `spare_issue_item`(`job_id`, `issue_id`, `appliance_id`, `spare_id`, `qty`, `rate`, `total`) VALUES ('$job_id', '$issue_id', '$appliance_id', '$spare_id', '$qty', '$rate', '$total')");
    }
    header("location:job-entry.php");
    exit;
}
?>