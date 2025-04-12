<?php
require_once("../landingpage/antall_kunder.php"); // Hent antall kunder
?>

<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Landing Page</title>
  <link rel="stylesheet" href="../css/landingpage.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down" />
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <script defer src="landingpage.js"></script>
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

<!-- Inkluderer header fra header.html -->
<div id="header">
    <?php include("../header/header.php"); ?>
</div>

<div class="headline-container">
    <h1 class="text-3xl font-light headline-left">KUNDEGRUPPER</h1>

    <div class="button-container">
    <a href="#" onclick="redirectToPage('registrer_borettslag/registrer_borettslaghtml.php')">
        <button class="secondaryBTN" id="nyKundeBtn"><span class="material-icons pil">add</span></button>
      </a>
    </div>
</div>  



  <!-- Shared Container for consistent alignment -->
  <div class="container">
   <!-- Card Grid -->
    <section class="card-container">
      <div class="category-row">
        <div class="card" id="privatkunde">
          <a href="#" onclick="redirectToPage('/liste_privatkunde/privatkunde_liste.php')">
            <div class="image-placeholder"></div>
              <div class="card-text">
                <h2>Privatkunder</h2>
                <p><?php echo $antallPrivatkunder; ?></p>
              </div>
          </a>
        </div>

        <div class="card" id="bedriftkunde">
          <a href="#" onclick="redirectToPage('liste_bedriftkunde/bedriftkunde_liste.php')"> 
            <div class="image-placeholder"></div>
            <div class="card-text">
              <h2>Bedriftskunder</h2>
              <p><?php echo $antallBedriftskunder; ?></p>
            </div>
          </a>
        </div>

        <div class="card" id="borettslag">
          <a href="#" onclick="redirectToPage('liste_borettslag/borettslag_liste.php')">
            <div class="image-placeholder"></div>
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