<?php
require_once("../db.php");

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "ID mangler"]);
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM privatkunde WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Fant ikke privatkunde med id $id"]);
}
?>