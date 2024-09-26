<?php
ob_start();
session_start();
include "config.php";
$id = $_GET['id'];
$query = "DELETE FROM suppliers WHERE supplier_id = '$id'";
$result = mysqli_query($con, $query);

header("Location: supplier.php");
exit;
?>