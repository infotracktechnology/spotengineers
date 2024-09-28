<?php
    ob_start();
    session_start();
    include_once 'config.php';

    if(isset($_POST['submit'])) {

        $name = $_POST['Supplier_Name'];

        $address1 = $_POST['Address1'];
        $address2 = $_POST['Address2'];
        $state = $_POST['State'];
        $district = $_POST['District'];
        $city = $_POST['City'];
        $pincode = $_POST['Pincode'];

        $account_no = $_POST['Account_no'];
        $bank_name = $_POST['Bank_name'];
        $ifsc = $_POST['IFSC'];
        $branch_name = $_POST['Branch_name'];
        $gst = $_POST['GST'];

        $mobile1 = $_POST['Mobile_one'];
        $mobile2 = $_POST['Mobile_two'];
        $landline = $_POST['Landline'];
        $fax = $_POST['Fax'];
        $email = $_POST['Mail'];
        $website = $_POST['Website'];
        
        $sql = "INSERT INTO `suppliers` (`supplier_name`, `mobile1`, `mobile2`, `landline`, `fax`, `mail`, `website`, `address1`, `address2`, `state`, `district`, `city`, `pincode`, `account_no`, `bank_name`, `ifsc`, `branch_name`, `gst`) VALUES ('$name', '$mobile1', '$mobile2', '$landline', '$fax', '$email', '$website', '$address1', '$address2','$state', '$district', '$city', '$pincode', '$account_no', '$bank_name', '$ifsc', '$branch_name','$gst');";
        $result = mysqli_query($con, $sql);
        
        header("Location: supplier.php");
        exit;
    }
?>