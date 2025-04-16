<?php
// Koble til databasen
include("../db2.php");

// Start session hvis ikke startet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hent brukernavn og passord fra session
$username = isset($_SESSION['db_username']) ? $_SESSION['db_username'] : "Ukjent Bruker";
$password = isset($_SESSION['db_password']) ? $_SESSION['db_password'] : null;

// Standard rolle er 'Bruker'
$rolle = "Bruker";

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("❌ Ikke logget inn. Vennligst logg inn først.");
}

// Hent rettigheter med SHOW GRANTS for å sjekke admin-status
try {
    $sql = sprintf("SHOW GRANTS FOR '%s'", $username);
    $stmt = $pdo->query($sql);

    $isAdmin = false;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $grantString = implode(" ", $row);
        if (stripos($grantString, "`adminbruker`") !== false) {
            $isAdmin = true;
        }
    }

    $rolle = $isAdmin ? "Admin" : "Bruker";

} catch (PDOException $e) {
    echo "Feil ved henting av brukerrettigheter: " . $e->getMessage();
}
?>