<?php
ob_start();
session_start();
include "config.php";
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $address_line_1 = $_POST['address_line_1'];
    $address_line_2 = $_POST['address_line_2'];
    $city = $_POST['city'];
    $doj = $_POST['doj'];
    $experience = $_POST['experience'];
    $aadhar = $_POST['aadhar'];
    $pan = $_POST['pan'];
    $salary = $_POST['salary'];
    $photo = $_POST['photo'];
    $acc_no = $_POST['acc_no'];
    $ifsc = $_POST['ifsc'];
    $branch = $_POST['branch'];

    if (isset($_FILES['photo'])) {
        $photo = $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], 'assets/upload/' . $photo);
        $photo = 'assets/upload/' . $photo;
    }

    $sql="UPDATE employee SET name='$name',dob='$dob',phone='$phone',address_line_1='$address_line_1',address_line_2='$address_line_2',city='$city',doj='$doj',experience='$experience',aadhar='$aadhar',pan='$pan',salary='$salary',photo='$photo',acc_no='$acc_no',ifsc='$ifsc',branch='$branch' WHERE id = '$id'";
    $result = mysqli_query($con, $sql);
    
    header("location:employee.php");
    exit;
}
    
?>