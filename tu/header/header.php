<?php
include("../db2.php"); // Inkluder databasen

// Start session og hent brukernavn
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    //echo "<p>SQL-spørring: $sql</p>"; // Skriv ut SQL-spørringen før den kjøres

    // Forbered og kjør SQL-spørringen
    $stmt = $pdo->query($sql);

    // Variabel for å sjekke om brukeren er medlem av adminrollen
    $isAdmin = false;

    // Gå gjennom resultatene
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $grantString = implode(" ", $row);

        // Sjekk om brukeren er medlem av adminrollen
        if (stripos($grantString, "`adminbruker`") !== false) {
            $isAdmin = true;
        }
    }

    // Sett riktig rolle basert på om brukeren er admin eller ikke
    if ($isAdmin) {
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
    <title>Header med Dropdown</title>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down,arrow_drop_up" />

    <!-- Egen CSS -->
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>

<header id="header"> 
    <div class="logo">
        <a href="#" onclick="redirectToPage('landingpage/landingpage.php')">
            <img src="../IMG/logo.png" alt="Fasade Produkter">
        </a>
    </div>

    <div class="user-menu">
        <button id="userMenuButton" class="profile">
            <div class="profile-circle">??</div> <!-- Initialer vises her -->
            <span class="material-symbols-outlined" id="dropdownArrow">arrow_drop_down</span>
        </button>
        <div id="dropdownMenu" class="dropdown-menu hidden">
            <!-- Ny bruker knapp som kun vises for admin, plassert øverst i menyen -->
            <?php if ($isAdmin): ?>
                <a href="../admin/registrer_bruker(admin).php">Ny Bruker</a>
                <a href="../admin/brukeroversikt.php" class="admin-dropdown-item">Brukeroversikt</a> <!-- Skjult admin knapp -->
            <?php endif; ?>

            <!-- Andre menyvalg -->
            <a href="#" id="profileLink">Profil</a>
            <a href="../profil/oppdaterprofil.php">Endre passord</a>
            <a href="../header/logout.php">Logg ut</a>
        </div>
    </div>
</header>

<script src="../header/include.js"></script> <!-- Inkluderer header.js -->
</body>
</html>
