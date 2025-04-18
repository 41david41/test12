<?php
// Kobler til databasen
require_once("../db.php");

// Sjekker om ID er oppgitt i URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Henter borettslag med oppgitt ID
    $stmt = $pdo->prepare("SELECT * FROM borettslagkunde WHERE id = ?");
    $stmt->execute([$id]);
    $borettslag = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($borettslag) {
        // Hvis funnet, returneres som JSON
        header('Content-Type: application/json');
        echo json_encode($borettslag);
    } else {
        // Hvis ikke funnet, returner 404-feil
        http_response_code(404);
        echo json_encode(["error" => "Ingen borettslag funnet med ID $id"]);
    }
} else {
    // Hvis ID mangler, returner 400-feil
    http_response_code(400);
    echo json_encode(["error" => "ID mangler"]);
}
?>
