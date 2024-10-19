<?php
ob_start();
session_start();
include('../config.php');
if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = ($_POST['password']);
    $sql = "SELECT * FROM users WHERE name = '$username'";

    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        if(password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['name'];
            $_SESSION['userid'] = $row['id'];
            $_SESSION['user_role'] = $row['role'];
            header("Location: ../welcome.php");
            exit;
        }else {
            $_SESSION['error'] = 'Enter the correct password';
            header("Location: ../index.php");
            exit;
        }
    } 
}

?>
