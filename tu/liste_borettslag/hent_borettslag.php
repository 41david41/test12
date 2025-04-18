<?php
// Inkluderer databaseoppsett
require_once("../db.php"); // Tilpass banen hvis nødvendig

// Setter content-type til JSON med UTF-8-tegnsett
header('Content-Type: application/json; charset=utf-8');

try {
    // Henter alle borettslag fra databasen
    $stmt = $pdo->query("SELECT * FROM borettslagkunde");
    $borettslag = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Returnerer resultatet som JSON uten at unicode-tegn blir escape't
    echo json_encode($borettslag, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // Returnerer en feilmelding hvis databaseoperasjonen feiler
    echo json_encode(["error" => "Databasefeil: " . $e->getMessage()]);
}
?>
