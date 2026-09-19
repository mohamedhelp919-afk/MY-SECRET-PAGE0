<?php
header('Content-Type: application/json');

// الاتصال بقاعدة البيانات
$db = new mysqli("localhost", "root", "password", "database_name");

if ($db->connect_error) {
    die(json_encode(["error" => "فشل الاتصال بقاعدة البيانات"]));
}

$order_code = isset($_GET['order_code']) ? $_GET['order_code'] : '';

$stmt = $db->prepare("SELECT status, card_code FROM orders WHERE order_code = ?");
$stmt->bind_param("s", $order_code);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['status'] === 'paid') {
        echo json_encode([
            'status' => 'paid',
            'card_code' => $row['card_code']
        ]);
    } else {
        echo json_encode(['status' => 'pending']);
    }
} else {
    echo json_encode(['status' => 'not_found']);
}

$stmt->close();
$db->close();
?>
