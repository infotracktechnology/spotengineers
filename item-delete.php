<?php
ob_start();
session_start();
include "config.php";
$id = $_GET['id'];
$query = "DELETE FROM items WHERE item_id = '$id'";
$result = mysqli_query($con, $query);

header("Location: items.php");
exit;

?>