<?php
// Inkluderer databaseoppsett
require_once("../db.php"); // Juster sti om nødvendig

// Setter header for JSON med UTF-8-tegnsett
header('Content-Type: application/json; charset=utf-8');

try {
    // Henter alle privatkunder fra databasen
    $stmt = $pdo->query("SELECT * FROM privatkunde");
    $privatkunde = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Returnerer resultat som JSON, uten unicode escaping
    echo json_encode($privatkunde, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // Feilhåndtering ved databasefeil
    echo json_encode(["error" => "Databasefeil: " . $e->getMessage()]);
}
?>
