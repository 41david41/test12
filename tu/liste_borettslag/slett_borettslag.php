<?php
// Inkluderer databaseoppsett
require_once("../db.php");

// Sjekker at forespørselen er POST og at ID er sendt med
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    try {
        // Forbereder og utfører sletting av borettslag basert på ID
        $stmt = $pdo->prepare("DELETE FROM borettslagkunde WHERE id = :id");
        $stmt->execute([":id" => $id]);

        // Går tilbake til listen etter vellykket sletting
        header("Location: borettslag_liste.php");
        exit;
    } catch (PDOException $e) {
        // Feilmelding dersom slettingen feiler
        echo "Feil ved sletting: " . htmlspecialchars($e->getMessage());
    }
}
?>
