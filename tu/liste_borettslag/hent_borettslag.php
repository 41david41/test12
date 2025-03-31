<?php
require_once("../db.php"); // Tilpass banen hvis nødvendig

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $pdo->query("SELECT * FROM borettslagkunde");
    $borettslag = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($borettslag, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(["error" => "Databasefeil: " . $e->getMessage()]);
}
?>
