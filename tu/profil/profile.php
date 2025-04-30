<?php
// Hent backendlogikk
include("profile_backend.php");
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brukerprofil</title>

    <!-- Stilark og fonter -->
    <link rel="stylesheet" href="../css/profil.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">

    <!-- Tailwind og JS -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="../redirectToPage.js"></script>
</head>
<body>

    <!-- Header -->
    <div id="header">
        <?php include("../header/header.php"); ?>
    </div>

    <!-- Profilkort -->
    <div class="profil-container">
        <div class="profil-card">
            <div class="profil-image">
                <?php
                    // Dynamisk bildevisning: forsøk å finne brukerens profilbilde
                    $profileImagePath = "/bilder/" . $username . ".jpeg";

                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $profileImagePath)) {
                        echo "<img src='$profileImagePath' alt='Profilbilde' class='profil-img'>";
                    } else {
                        echo "<img src='/bilder/default.jpeg' alt='Standard profilbilde' class='profil-img'>";
                    }
                ?>
            </div>
        
         
            <div class="profil-info">
                <p>Brukernavn: <?php echo htmlspecialchars($username); ?></p>
                <p>E-post: <?php echo htmlspecialchars($epost); ?></p>
                <p>Rolle: <?php echo htmlspecialchars($rolle); ?></p>
                <br>
                <div class="profil-button">
                    <button class="secondaryBTN"><p>Endre passord</p></button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
