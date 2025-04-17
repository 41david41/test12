<?php
// Hent backendlogikk
include("header_backend.php");
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header med Dropdown</title>

    <!-- Google Fonts og Material-symboler -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down,arrow_drop_up" />

    <!-- Egendefinert stil for header -->
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>

<!-- Hovedheader som vises øverst på siden -->
<header id="header"> 

    <!-- Logo med klikkbar lenke til landingssiden -->
    <div class="logo">
        <a href="#" onclick="redirectToPage('landingpage/landingpage.php')">
            <img src="../IMG/logo.png" alt="Fasade Produkter">
        </a>
    </div>

    <!-- Brukermeny til høyre med profilknapp og dropdown -->
    <div class="user-menu">

        <!-- Knapp som viser profilbilde og pil for å åpne menyen -->
        <button id="userMenuButton" class="profile">
            <div class="profile-circle">??</div> <!-- Her kan initialer vises -->
            <span class="material-symbols-outlined" id="dropdownArrow">arrow_drop_down</span>
        </button>

        <!-- Selve dropdown-menyen som åpnes når man klikker -->
        <div id="dropdownMenu" class="dropdown-menu hidden">

            <!-- Vises kun for administratorer -->
            <?php if ($isAdmin): ?>
                <a href="../admin/registrer_bruker(admin).php">Ny Bruker</a>
                <a href="../admin/brukeroversikt.php" class="admin-dropdown-item">Brukeroversikt</a>
            <?php endif; ?>

            <!-- Vanlige brukervalg -->
            <a href="#" id="profileLink">Profil</a>
            <a href="../profil/change_password.php">Endre passord</a>
            <a href="../header/logout.php">Logg ut</a>
        </div>
    </div>
</header>

<!-- JavaScript for å håndtere dropdown og annet interaktivt -->
<script src="../header/include.js"></script>

</body>
</html>
