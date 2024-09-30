<?php
ob_start();
session_start();
include "config.php";

if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    
 
    if (isset($_POST['purchase_items']) && !empty($_POST['purchase_items'])) {
        $purchase_returns_query = "INSERT INTO `purchase_returns` 
            (`return_no`, `return_date`, `supplier_id`, `receipt_no`, `total_price`, `total_tax`, `grand_total`, `created_at`) 
            VALUES ('$return_no', '$return_date', (SELECT supplier_id FROM suppliers WHERE supplier_name = '$supplier_name'), '$receipt_no', '$total_price', '$total_tax', '$grand_total', NOW())";
        
        if (mysqli_query($con, $purchase_returns_query)) {
            $return_no = mysqli_insert_id($con);
            
        
            $purchase_items = json_decode($_POST['purchase_items'], true);

            foreach ($purchase_items as $item) {
                $item_id = $item['item_id'];
                $rate = $item['rate'];
                $qty = $item['quantity'];
                $tax_percentage = $item['tax_percentage'];
                $tax_amount = $item['tax_amount'];
                $total = $item['total'];
                $created_at = date('Y-m-d H:i:s');

                $purchase_returns_items_query = "INSERT INTO `purchase_returns_items` 
                    (`return_no`, `item_id`, `rate`, `qty`, `tax_percentage`, `tax_amount`, `total`, `created_at`) 
                    VALUES ('$return_no', '$item_id', '$rate', '$qty', '$tax_percentage', '$tax_amount', '$total', '$created_at')";
                
                mysqli_query($con, $purchase_returns_items_query);

             
                mysqli_query($con, "UPDATE `purchase_items` SET `quantity` = `quantity` - '$qty' WHERE `item_id` = '$item_id'");
            }

            
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "No items to process.";
    }
}

?>
