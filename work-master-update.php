<?php
    ob_start();
    session_start();
    include_once 'config.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $category = $_POST['category'];
        $amount = $_POST['amount'];
        $sql = "UPDATE `work` SET `title` = '$title', `category` = '$category', `amount` = '$amount' WHERE `id` = '$id'";
        $result = mysqli_query($con, $sql);

        header("location:work-master.php");
        exit;
    }

?>
