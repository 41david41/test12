<?php
include("../db2.php"); // Inkluder databasen

// Start session og hent brukernavn
session_start();
$username = isset($_SESSION['db_username']) ? $_SESSION['db_username'] : "Ukjent Bruker";
$password = isset($_SESSION['db_password']) ? $_SESSION['db_password'] : null;

// Standard rolle
$rolle = "Bruker"; // Antar at brukeren er en vanlig bruker som standard

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("❌ Ikke logget inn. Vennligst logg inn først.");
}

// Hent brukerens rettigheter med SHOW GRANTS ved hjelp av PDO
try {
    // Dynamisk bygg SQL-spørringen med brukernavnet
    $sql = sprintf("SHOW GRANTS FOR '%s'", $username);  // Sett inn brukernavnet i SQL-spørringen

    // Forbered og kjør SQL-spørringen
    $stmt = $pdo->query($sql);

    // Variabel for å sjekke om brukeren er medlem av testrole
    $isTestRoleMember = false;

    // Gå gjennom resultatene
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $grantString = implode(" ", $row);

        // Sjekk om brukeren er medlem av testrole
        if (stripos($grantString, "`adminbruker`") !== false) {
            $isTestRoleMember = true;
            break; // Stopper så snart vi finner rollen
        }
    }

    // Sett riktig rolle
    if ($isTestRoleMember) {
        $rolle = "Admin";
    } else {
        $rolle = "Bruker";
    }

} catch (PDOException $e) {
    echo "Feil ved henting av brukerrettigheter: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brukerprofil</title>
    <link rel="stylesheet" href="../css/profil.css"> <!-- Link til ekstern CSS-fil -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down,arrow_drop_up" />
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

    <div id="header">
        <?php include("../header/header.php"); ?>
    </div>

    <div class="profil-container">
        <div class="profil-card">
            <div class="profil-image">
                <!-- Profilbildet vises her -->
                <img src="profile.jpg" alt="Profilbilde" class="profil-img">
            </div>
            <div class="profil-info">
                <h2 class="profil-name"><?php echo htmlspecialchars($username); ?></h2>
                <p class="profil-role">Rolle: <?php echo htmlspecialchars($rolle); ?></p> <!-- Rolle vises her -->
            </div>
        </div>
    </div>

</body>
</html>