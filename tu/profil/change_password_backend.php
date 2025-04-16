<?php
// ===============================
// Backend: Endring av passord
// ===============================

// Feilhåndtering og logging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// Start session hvis den ikke allerede er startet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inkluder databaseforbindelse
include("../db2.php");

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['db_username']) || empty($_SESSION['db_username'])) {
    // Hvis ikke, send brukeren tilbake til login
    die("<script>alert('Ingen bruker funnet. Logg inn på nytt.'); window.location.href='../login/login.php';</script>");
}

$brukernavn = $_SESSION['db_username']; // Hent innlogget brukers brukernavn

// Sjekk om forespørselen kommer fra et skjema (POST-metode)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hent innsendte passord
    $oldPassword     = $_POST['old_password'];
    $newPassword     = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Sjekk om nye passord matcher
    if ($newPassword !== $confirmPassword) {
        die("<script>alert('De nye passordene matcher ikke.'); window.history.back();</script>");
    }

    try {
        // Kjør SQL for å oppdatere passord for brukeren i MySQL
        $sql = "ALTER USER '$brukernavn'@'%' IDENTIFIED BY '$newPassword'";
        $pdo->exec($sql);
        $pdo->exec("FLUSH PRIVILEGES"); // Sørg for at endringen trer i kraft

        // Loggfør suksess og informer brukeren
        error_log("✅ Passord oppdatert for '$brukernavn'");
        echo "<script>alert('Passordet er oppdatert!'); window.location.href='../login/login.html';</script>";
        exit;

    } catch (Exception $e) {
        // Loggfør og vis feilmelding ved feil
        error_log("❌ Feil ved oppdatering av passord: " . $e->getMessage());
        echo "<script>alert('Noe gikk galt. Vennligst prøv igjen.'); window.history.back();</script>";
    }
}
?>
