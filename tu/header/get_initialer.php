<?php
session_start();

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "??";  // Returner "??" hvis brukeren ikke er logget inn
    exit();
}

// Hent brukernavn fra session
$username = $_SESSION['db_username'];

// Hent første bokstav av brukernavnet som initialer
$initialer = strtoupper(substr($username, 0, 1));

// Returner initialene
echo $initialer;
?>
