<?php
if(isset($_POST['id'])){
    $supplier_id = $_POST['id'];
    // $supplier_id = 8;

    ob_start();
    session_start();    
    include 'config.php';
    $query = "SELECT address1, state, district, gst FROM suppliers WHERE supplier_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $supplier_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    $supplier = $result->fetch_assoc();

    echo json_encode([
        'address1' => $supplier['address1'],
        'state' => $supplier['state'],
        'district' => $supplier['district'],
        'gst' => $supplier['gst']
    ]);
}
?>
