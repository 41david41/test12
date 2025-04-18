<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tittel som vises i nettleserfanen -->
    <title>registrer_borettslag</title>
   
    <!-- Stilark og fonter -->
    <link rel="stylesheet" href="../css/registrer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

        <!-- Eksterne .js -->
    <script src="../redirectToPage.js"></script>
    <script src="../header/include.js"></script>
</head>
</head>

<body>
<!-- Inkluderer sideheader -->
<div id="header">
    <?php include("../header/header.php"); ?>
</div>

<!-- Toppseksjon med tilbakeknapp og valg for kundegruppe -->
<div class="headline-container">
  <button class="secondaryBTN" id="go_back" onclick="redirectToPage('liste_borettslag/borettslag_liste.php')">
    <span class="material-icons pil">arrow_back</span>
  </button>
  <div class="dropdown">
    <button class="dropdown-btn" id="kundegruppeBtn">
      BORETTSLAG <span class="material-symbols-outlined pil">arrow_drop_down</span>
    </button>
    <div class="dropdown-content">
      <a href="#" onclick="redirectToPage('registrer_privatkunde/registrer_privatkundehtml.php')">Privatkunde</a>
      <a href="#" onclick="redirectToPage('registrer_bedriftkunde/registrer_bedriftkundehtml.php')">Bedriftskunde</a>
    </div>
  </div>
</div>

<!-- Skjema for registrering/redigering av borettslag -->
<form method="POST" enctype="multipart/form-data" id="bedriftForm">
  <div class="container">
    <div class="form-container">

      <!-- Venstre kolonne: Organisasjons- og adresseinfo -->
      <div class="form-left">
        <div class="form-group"><input name="orgnr" type="text" placeholder="Organisasjonsnummer" pattern="^[0-9]{9}$" required></div>
        <div class="form-group"><input name="navn" type="text" placeholder="Navn på borettslag" required></div>
        <div class="form-group"><input name="styreleder" type="text" placeholder="Styreleder" required></div>
        <div class="form-group"><input name="adresse1" type="text" placeholder="Adresse 1" required></div>
        <div class="form-group"><input name="adresse2" type="text" placeholder="Adresse 2"></div>
      </div>

      <!-- Midtkolonne: kontakt- og stedinfo -->
      <div class="form-middel">
        <div class="split-input">
          <div class="form-group half-width"><input name="postnr" type="text" placeholder="PostNr." pattern="^[0-9]{4}$" required></div>
          <div class="form-group half-width"><input name="sted" type="text" placeholder="Sted" required></div>
        </div>
        <div class="form-group"><input name="epost" type="text" placeholder="E-post" required></div>
        <div class="form-group"><input name="telefon" type="text" placeholder="Telefonnummer" pattern="^[0-9]{8}$" required></div>
        <div class="form-group"><input name="kontaktperson" type="text" placeholder="Kontaktperson" required></div>
        <div class="form-group"><input name="kontaktpersonTlf" type="text" placeholder="Kontaktperson telefonnummer" pattern="^[0-9]{8}$" required></div>
      </div>

      <!-- Høyrekolonne: kommentar og filopplasting -->
      <div class="form-right">
        <div class="form-group"><textarea name="kommentar" placeholder="Kommentar"></textarea></div>

        <!-- Opplasting av bilde og PDF -->
        <div class="button-container">
          <input type="file" name="bilde" id="imageUpload" accept="image/*" hidden>
          <button id="bilde" class="fileinput" type="button" onclick="document.getElementById('imageUpload').click();">
            <span class="material-icons">image</span>
          </button>

          <input type="file" name="pdf" id="pdfUpload" accept="application/pdf" hidden>
          <button id="PDF" class="fileinput" type="button" onclick="document.getElementById('pdfUpload').click();">
            <span class="material-icons">picture_as_pdf</span>
          </button>
        </div>

        <!-- Send-knapp -->
        <div class="button-container">
          <button type="submit" class="primaryBTN">Registrer</button>
        </div>
      </div>

    </div>
  </div>

  <!-- Forhåndsvisning av opplastet bilde og PDF -->
  <div id="bildePreview" class="preview-container"></div>
  <div id="pdfPreview" class="preview-container"></div>
</form>

<!-- Eksterne scripts -->
<script src="registrer_borettslag.js"></script>
<script src="../preview.js"></script>
</body>
</html>
