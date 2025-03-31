<?php
session_start();

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("❌ Ikke logget inn. Vennligst logg inn først.");
}

// Hent brukernavn og passord fra session
$username = $_SESSION['db_username'];
$password = $_SESSION['db_password'];

$host = '10.196.243.25';  // MySQL-server
$dbname = 'kunde_tabeller';
$port = 3306;

try {
    // Opprett en databaseforbindelse med brukerens egne innloggingsdetaljer
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Feil ved tilkobling til databasen: " . $e->getMessage());
}
?>
