<?php
ob_start();
session_start();
include "config.php";
$deduction = -$_POST['deduction'];
$emp_id = $_POST['emp_id'];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $advance = $con->query("INSERT INTO employee_advance (emp_id, date, amount) VALUES ($emp_id, NOW(), $deduction)");
    if($advance){
        echo json_encode(['success' => true]);
    }
    else{
        echo json_encode(['success' => false]);
    }
}
?>