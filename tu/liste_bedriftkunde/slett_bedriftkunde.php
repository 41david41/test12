<?php
// Inkluderer databasekonfigurasjon
require_once("../db.php");

// Sjekker at forespørselen er POST og at ID er sendt med
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    try {
        // Forbereder og utfører sletting av kunden basert på ID
        $stmt = $pdo->prepare("DELETE FROM bedriftskunde WHERE id = :id");
        $stmt->execute([":id" => $id]);

        // Ved suksess, send brukeren tilbake til kundelisten
        header("Location: bedriftkunde_liste.php");
        exit;
    } catch (PDOException $e) {
        // Håndterer databasefeil
        echo "Feil ved sletting: " . htmlspecialchars($e->getMessage());
    }
}
?>
