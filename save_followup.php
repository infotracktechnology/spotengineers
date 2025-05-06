<?php

ob_start();
session_start();
include "config.php";

// Check user session
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}
header('Content-Type: application/json');

    extract($_POST);
    $job = $con->query("INSERT INTO `followup`(`job_entry_id`, `job_no`, `proposal_date`, `call_status`, `customer_id`,`employee_id`, `customer_phone`, `remarks`) VALUES ('$job_entry_id', '$job_no', '$proposal_date', '$call_status', '$customer_id','$employee_id', '$customer_phone', '$remarks');");
    $job_status = $con->query("UPDATE `job_entry` SET `followup_status`='$call_status' WHERE `id`='$job_entry_id';");
    if(!$job && !$job_status) {
            echo json_encode(['success' => false, 'message' => 'Database prepare error']);
            exit;
    }
    if($job) {
        $response = ['status' => 'success', 'message' => 'Remarks added successfully'];
       }else {
        $response = ['success' => false, 'message' => 'Error saving feedback: ' . $stmt->error];
       }
    echo json_encode($response);
// ob_start();
// session_start();
// include "config.php";

// // Check user session
// if (!isset($_SESSION['username'])) {
//     header("location:index.php");
//     exit;
// }

// if($_SERVER['REQUEST_METHOD'] == 'POST') {
//     extract($_POST);
//     $job = $con->query("INSERT INTO `followup`(`job_entry_id`, `job_no`, `proposal_date`, `call_status`, `customer_id`,`employee_id`, `customer_phone`, `remarks`) VALUES ('$job_entry_id', '$job_no', '$proposal_date', '$call_status', '$customer_id','$employee_id', '$customer_phone', '$remarks');");
//     if($job) {
//         echo '<script>alert("Remarks Added Successfully");</script>';
//         echo '<script>window.location.href="pending.php";</script>';
//        }else {
//         echo '<script>alert("Something went wrong");</script>';
//         echo '<script>window.location.href="pending.php";</script>';
//        }
//     //    echo '<script>alert("Remarks Added Successfully");</script>';
//     //    echo '<script>window.location.href="pending.php";</script>';
//    }

?>