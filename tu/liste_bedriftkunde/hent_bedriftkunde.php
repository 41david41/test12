<?php
require_once("../db.php"); // Juster banen om nødvendig

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $pdo->query("SELECT * FROM bedriftskunde");
    $bedriftskunde = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($bedriftskunde, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(["error" => "Databasefeil: " . $e->getMessage()]);
}
?>
