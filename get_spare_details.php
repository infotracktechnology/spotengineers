<?php
if(isset($_POST['id'])){
    $item_id = $_POST['id'];

    ob_start();
    session_start();
    include 'config.php';
    $query = "SELECT uom, mrp, selling_price FROM items WHERE item_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $item_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    $supplier = $result->fetch_assoc();

    echo json_encode([
        'unit' => $supplier['uom'],
        'mrp' => $supplier['mrp'],
        'rate' => $supplier['selling_price']
    ]);

}
?>
