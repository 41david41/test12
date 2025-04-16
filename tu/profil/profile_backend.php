<?php
include("../db2.php"); // Inkluder databasen

// Start session og hent brukernavn
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = isset($_SESSION['db_username']) ? $_SESSION['db_username'] : "Ukjent Bruker";
$password = isset($_SESSION['db_password']) ? $_SESSION['db_password'] : null;

$rolle = "Bruker"; // Standard rolle
$epost = "Ikke tilgjengelig"; // Standardverdi for e-post

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("❌ Ikke logget inn. Vennligst logg inn først.");
}

// Hent brukerens rolle via SHOW GRANTS
try {
    $sql = sprintf("SHOW GRANTS FOR '%s'", $username);
    $stmt = $pdo->query($sql);

    $isTestRoleMember = false;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $grantString = implode(" ", $row);
        if (stripos($grantString, "`adminbruker`") !== false) {
            $isTestRoleMember = true;
            break;
        }
    }

    $rolle = $isTestRoleMember ? "Admin" : "Bruker";

} catch (PDOException $e) {
    echo "Feil ved henting av brukerrettigheter: " . $e->getMessage();
}

// 🔍 Hent brukerens e-post fra user_details-tabellen
try {
    $stmt = $pdo->prepare("SELECT epost FROM user_details WHERE user = :brukernavn");
    $stmt->bindParam(':brukernavn', $username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && isset($result['epost'])) {
        $epost = $result['epost'];
    }

} catch (PDOException $e) {
    error_log("❌ Feil ved henting av e-post: " . $e->getMessage());
}
?>
