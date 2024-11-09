<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

// Include database configuration
include "config.php";

// Check if item_id is provided in the POST request
if (isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];

    // Delete the spare issue item from the database
    $delete_query = "DELETE FROM spare_issue_item WHERE id = '$item_id'";

    if ($con->query($delete_query)) {
        echo 'Item deleted successfully';
    } else {
        echo 'Error deleting item: ' . $con->error;
    }
} else {
    echo 'Item ID not provided';
}
?>
