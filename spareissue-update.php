<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit;
}

// Include database configuration
include "config.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the data from the form
    $issue_no = $_POST['issue_no'];
    $qty = $_POST['qty'];    // Array of quantities
    $rate = $_POST['rate'];  // Array of rates
    $total = $_POST['total'];  // Array of totals
    $appliance_ids = $_POST['appliance_id'];  // Array of appliance IDs
    $spare_ids = $_POST['spare_id'];  // Array of spare IDs

    // Fetch the issue ID from the issue_no
    $get_issue_id_query = "SELECT id FROM spare_issue WHERE issue_no = '$issue_no'";
    $result = $con->query($get_issue_id_query);
    $issue = $result->fetch_object();

    if (!$issue) {
        echo "Issue not found.";
        exit;
    }

    $issue_id = $issue->id; // Get the issue_id from the database

    // Use prepared statement to update the `spare_issue_item` records based on issue_id
    $update_item_query = "
        UPDATE spare_issue_item 
        SET qty = ?, rate = ?, total = ? 
        WHERE issue_id = ? 
        AND appliance_id = ? 
        AND spare_id = ?
    ";

    // Prepare the SQL statement
    $stmt = $con->prepare($update_item_query);

    if (!$stmt) {
        echo "Error preparing the statement: " . $con->error;
        exit;
    }

    // Bind the parameters for the prepared statement
    $stmt->bind_param("iiiiii", $new_qty, $new_rate, $new_total, $issue_id, $new_appliance_id, $new_spare_id);

    // Update each row based on the appliance_id and spare_id
    foreach ($qty as $index => $quantity) {
        $new_qty = $quantity;
        $new_rate = $rate[$index];
        $new_total = $total[$index];
        $new_appliance_id = $appliance_ids[$index];  // Get appliance_id for the row
        $new_spare_id = $spare_ids[$index];  // Get spare_id for the row

        // Execute the query for the current row
        if (!$stmt->execute()) {
            echo "Error updating spare issue item: " . $stmt->error;
            exit;
        }
    }

    // Close the prepared statement
    $stmt->close();

    // If everything is successful, redirect back with success message
    header("Location: spare-return.php?issue_no=$issue_no&success=true");
    exit;
}
?>
