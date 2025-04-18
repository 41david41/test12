<?php
// Henter én privatkunde basert på ID
require_once("../db.php");

// Sjekker at ID er oppgitt i URL
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "ID mangler"]);
    exit;
}

$id = $_GET['id'];

// Forbereder og utfører spørring mot databasen
$stmt = $pdo->prepare("SELECT * FROM privatkunde WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data) {
    // Returnerer data som JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // Returnerer 404 hvis kunden ikke finnes
    http_response_code(404);
    echo json_encode(["error" => "Fant ikke privatkunde med id $id"]);
}
?>
