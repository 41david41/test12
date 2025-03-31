<?php
session_start(); // Start sesjonen

// Fjern alle sesjonsvariabler
session_unset();

// Ødelegg sesjonen
session_destroy();

// Send brukeren tilbake til login-siden
header("Location: ../login/login.html");
exit();
?>
