<?php
include 'config.php'; 
function fetchSupplierDetails($con, $receipt_no) {
    $stmt = $con->prepare("SELECT s.supplier_id, s.supplier_name, s.address1
                            FROM purchase p
                            JOIN suppliers s ON p.supplier = s.supplier_id
                            WHERE p.receipt_no = ?");
    $stmt->bind_param("i", $receipt_no);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
function fetchPurchasedProducts($con, $supplier_id) {
    $stmt = $con->prepare("SELECT i.item_id, i.name 
                            FROM purchase_items pi
                            JOIN purchase p ON pi.purchase_id = p.purchase_id
                            WHERE p.supplier = ?");
    $stmt->bind_param("i", $supplier_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];

    if (isset($_POST['receipt_no'])) {
        $purchase = fetchSupplierDetails($con, $_POST['receipt_no']);
        $response = $purchase ? [
            'supplier_id' => $purchase['supplier_id'],
            'supplier_name' => $purchase['supplier_name'],
            'address' => $purchase['address1']
        ] : ['error' => 'No supplier found for the given receipt number'];
    } elseif (isset($_POST['supplier_id'])) {
        $response = fetchPurchasedProducts($con, $_POST['supplier_id']);
    }

    echo json_encode($response);
}


?>

