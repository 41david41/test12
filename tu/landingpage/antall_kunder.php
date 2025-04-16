<?php
require_once("../db.php"); // Inkluderer databaseforbindelsen

try {
    // Sjekker om databaseobjektet er tilgjengelig
    if (!isset($pdo)) {
        throw new Exception("❌ Databaseforbindelse ikke funnet.");
    }

    // Hent antall privatkunder
    $stmt1 = $pdo->query("SELECT COUNT(*) AS count FROM privatkunde");
    $antallPrivatkunder = $stmt1->fetch(PDO::FETCH_ASSOC)['count'];

    // Hent antall bedriftskunder
    $stmt2 = $pdo->query("SELECT COUNT(*) AS count FROM bedriftskunde");
    $antallBedriftskunder = $stmt2->fetch(PDO::FETCH_ASSOC)['count'];

    // Hent antall borettslag
    $stmt3 = $pdo->query("SELECT COUNT(*) AS count FROM borettslagkunde");
    $antallBorettslag = $stmt3->fetch(PDO::FETCH_ASSOC)['count'];

} catch (PDOException $e) {
    // Logger databasefeil
    error_log("Databasefeil: " . $e->getMessage());
    $antallPrivatkunder = $antallBedriftskunder = $antallBorettslag = 0;

} catch (Exception $e) {
    // Logger generell feil
    error_log("Feil: " . $e->getMessage());
    $antallPrivatkunder = $antallBedriftskunder = $antallBorettslag = 0;
}
?>
