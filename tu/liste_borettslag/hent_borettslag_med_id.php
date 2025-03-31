<?php
require_once("../db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM borettslagkunde WHERE id = ?");
    $stmt->execute([$id]);
    $borettslag = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($borettslag) {
        header('Content-Type: application/json');
        echo json_encode($borettslag);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Ingen borettslag funnet med ID $id"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "ID mangler"]);
}
?>