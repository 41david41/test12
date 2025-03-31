<?php
require_once("../db.php"); // Juster sti om nødvendig

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $pdo->query("SELECT * FROM privatkunde");
    $privatkunde = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($privatkunde, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(["error" => "Databasefeil: " . $e->getMessage()]);
}
?>
