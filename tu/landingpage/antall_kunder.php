<?php
require_once("../db.php"); // Inkluder databaseforbindelsen

try {
    // Sjekk om $pdo er definert
    if (!isset($pdo)) {
        throw new Exception("❌ Databaseforbindelse ikke funnet.");
    }

    // Teller antall kunder i hver tabell
    $stmt1 = $pdo->query("SELECT COUNT(*) AS count FROM privatkunde");
    $antallPrivatkunder = $stmt1->fetch(PDO::FETCH_ASSOC)['count'];

    $stmt2 = $pdo->query("SELECT COUNT(*) AS count FROM bedriftskunde");
    $antallBedriftskunder = $stmt2->fetch(PDO::FETCH_ASSOC)['count'];

    $stmt3 = $pdo->query("SELECT COUNT(*) AS count FROM borettslagkunde");
    $antallBorettslag = $stmt3->fetch(PDO::FETCH_ASSOC)['count'];
} catch (PDOException $e) {
    error_log("Databasefeil: " . $e->getMessage()); // Logger feilen
    $antallPrivatkunder = $antallBedriftskunder = $antallBorettslag = 0;
} catch (Exception $e) {
    error_log("Feil: " . $e->getMessage()); // Logger feilen
    $antallPrivatkunder = $antallBedriftskunder = $antallBorettslag = 0;
}
?>
