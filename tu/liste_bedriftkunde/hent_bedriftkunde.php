<?php
// Kobler til databasen via felles db-konfigurasjon
require_once("../db.php"); // Juster banen om filstrukturen er annerledes

// Setter respons-header til JSON med UTF-8-koding
header('Content-Type: application/json; charset=utf-8');

try {
    // Henter alle bedriftskunder fra databasen
    $stmt = $pdo->query("SELECT * FROM bedriftskunde");
    $bedriftskunde = $stmt->fetchAll(PDO::FETCH_ASSOC); // Returnerer som assosiativ array

    // Returnerer resultatene som JSON uten unicode-escaping (slik at æøå vises korrekt)
    echo json_encode($bedriftskunde, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // Ved databasefeil, returner en feilmelding som JSON
    echo json_encode(["error" => "Databasefeil: " . $e->getMessage()]);
}
?>
