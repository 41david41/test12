<?php
// Kobler til databasen via felles db-konfigurasjon
require_once("../db.php");

// Sjekker om ID er sendt med i forespørselen (GET-parameter)
if (!isset($_GET['id'])) {
    http_response_code(400); // Returnerer HTTP 400 Bad Request
    echo json_encode(["error" => "ID mangler"]);
    exit;
}

// Henter ID-en fra URL-parameteret
$id = $_GET['id'];

// Forbereder SQL-spørring for å hente kundeinformasjon basert på ID
$stmt = $pdo->prepare("SELECT * FROM bedriftskunde WHERE id = ?");
$stmt->execute([$id]);

// Henter resultatet som en assosiativ array
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data) {
    // Hvis data finnes, send som JSON med korrekt header
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // Hvis ingen kunde med gitt ID finnes, send 404-feil
    http_response_code(404);
    echo json_encode(["error" => "Fant ikke bedriftskunde med id $id"]);
}
?>
