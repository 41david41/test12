<?php
// Kobler til databasen
require_once("../db.php");

// Sjekker at forespørselen er en POST og at ID er sendt med
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    try {
        // Sletter kunden med oppgitt ID fra databasen
        $stmt = $pdo->prepare("DELETE FROM privatkunde WHERE id = :id");
        $stmt->execute([":id" => $id]);

        // Videresender til kundelisten etter sletting
        header("Location: privatkunde_liste.php");
        exit;
    } catch (PDOException $e) {
        // Feilmelding hvis sletting feiler
        echo "Feil ved sletting: " . htmlspecialchars($e->getMessage());
    }
}
?>
