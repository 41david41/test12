<?php
// Henter antall registrerte kunder fra en ekstern PHP-fil
require_once("../landingpage/antall_kunder.php");
?>

<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Landing Page</title>

  <!-- Fonter og ikoner -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" >

  <!-- Egne stilark -->
  <link rel="stylesheet" href="../css/landingpage.css">
  <link rel="stylesheet" href="../css/header.css">

  <!-- JavaScript -->
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <script src="../redirectToPage.js"></script>
</head>

<body>
  <!-- Toppseksjon med navigasjonsmeny -->
  <div id="header">
    <?php include("../header/header.php"); ?>
  </div>

  <!-- Overskrift og knapp for registrering av ny kunde -->
  <div class="headline-container">
    <h1 class="text-3xl">KUNDEGRUPPER</h1>
    <div class="button-container">
      <a href="#" onclick="redirectToPage('registrer_borettslag/registrer_borettslaghtml.php')">
        <button class="secondaryBTN">
          <span class="material-icons pil">add</span>
        </button>
      </a>
    </div>
  </div>

  <!-- Hovedinnhold som viser kort for hver kundegruppe -->
  <div class="container">
    <section class="card-container">
      <div class="category-row">

        <!-- Kort for privatkunder -->
        <div class="card" id="privatkunde">
          <a href="#" onclick="redirectToPage('/liste_privatkunde/privatkunde_liste.php')">
            <div class="image-placeholder"></div>
            <div class="card-text">
              <h2>Privatkunder</h2>
              <p><?php echo $antallPrivatkunder; ?></p>
            </div>
          </a>
        </div>

        <!-- Kort for bedriftskunder -->
        <div class="card" id="bedriftkunde">
          <a href="#" onclick="redirectToPage('liste_bedriftkunde/bedriftkunde_liste.php')">
            <div class="image-placeholder"></div>
            <div class="card-text">
              <h2>Bedriftskunder</h2>
              <p><?php echo $antallBedriftskunder; ?></p>
            </div>
          </a>
        </div>

        <!-- Kort for borettslag -->
        <div class="card" id="borettslag">
          <a href="#" onclick="redirectToPage('liste_borettslag/borettslag_liste.php')">
            <div class="image-placeholder">
              <img src="../IMG/borettslag-bilde">
            </div>
            <div class="card-text">
              <h2>Borettslag</h2>
              <p><?php echo $antallBorettslag; ?></p>
            </div>
          </a>
        </div>

      </div>
    </section>
  </div>
</body>
</html>
