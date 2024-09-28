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
        
        $sql = "UPDATE `suppliers` SET `supplier_name` = '$name', `mobile1` = '$mobile1', `mobile2` = '$mobile2', `landline` = '$landline', `fax` = '$fax', `mail` = '$email', `website` = '$website', `address1` = '$address1', `address2` = '$address2', `state` = '$state', `district` = '$district', `city` = '$city', `pincode` = '$pincode', `account_no` = '$account_no', `bank_name` = '$bank_name', `ifsc` = '$ifsc', `branch_name` = '$branch_name', `gst` = '$gst' WHERE `supplier_id` = '$_GET[id]';";

        $result = mysqli_query($con, $sql);
        
        if($result) {
            header("Location: supplier.php");
            exit;
        } else {
            echo "<script>alert('Supplier not Updated.');</script>";
            header("Location: supplier.php");
        }
    }
?>
