<?php
// Start session og hent backendlogikk
session_start();
include("change_password_backend.php");
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endre passord</title>

    <!-- Fonter og stilark -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="../css/oppdaterprofil.css">

    <!-- Tailwind og JS -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="../redirectToPage.js"></script>

    <style>
        #header {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        #header header {
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Felles header -->
    <div id="header">
        <?php include("../header/header.php"); ?>
    </div>

    <!-- Overskrift -->
    <div class="headline-container">
        <h1 class="text-3xl font-light">Endre passord</h1>
    </div>

    <!-- Skjema for passordoppdatering -->
    <div class="container">
        <form method="POST">
            <input type="password" name="old_password" placeholder="Gammelt passord" required>
            <input type="password" name="new_password" placeholder="Nytt passord" required>
            <input type="password" name="confirm_password" placeholder="Bekreft nytt passord" required>

            <button class="rød-knapp" type="submit">Endre passord</button>
        </form>
    </div>

</body>
</html>
