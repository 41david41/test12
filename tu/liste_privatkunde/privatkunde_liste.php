<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>privatkunde_liste</title>

    <!-- Stiler og fonter -->
    <link rel="stylesheet" href="../css/liste.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down" />

    <!-- Tailwind + JS for navigasjon -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="../redirectToPage.js"></script>   
</head>

<body>
<!-- Header -->
<div id="header">
  <?php include("../header/header.php"); ?>
</div>

<!-- Toppseksjon med kundegruppevelger og handlingsknapper -->
<div class="headline-container">
    <div class="dropdown">
        <button class="dropdown-btn" id="kundegruppeBtn">
            PRIVATKUNDER <span class="material-symbols-outlined pil">arrow_drop_down</span>
        </button>
        <div class="dropdown-content">
            <a href="#" onclick="redirectToPage('liste_borettslag/borettslag_liste.php')">Borettslag</a>
            <a href="#" onclick="redirectToPage('liste_bedriftkunde/bedriftkunde_liste.php')">Bedriftskunder</a>
        </div>
    </div>

    <div class="button-container">       
        <!-- Registrer ny kunde -->
        <a href="#" onclick="redirectToPage('registrer_privatkunde/registrer_privatkundehtml.php')">
            <button class="primaryBTN" id="nyKundeBtn">
                <span class="material-icons pil">add</span> Ny kunde 
            </button>
        </a>

        <!-- Eksportknapp -->
        <button onclick="exportToCSV()" class="secondaryBTN">
            <span class="material-icons pil">download</span> CSV
        </button>
    </div>
</div>

<!-- Søke- og visningsalternativer -->
<div class="sticky-header">
    <div class="visning-sok-wrapper">    
        <div class="button-container">
            <input type="text" id="sokefelt" placeholder="Søk..." oninput="filtrerKunder()"> 
            <button class="secondaryBTN" id="filter">
                <span class="material-icons pil">filter_alt</span>            
            </button>
        </div>    

        <!-- Grid eller listevisning -->
        <div class="toggle">
            <div id="gridBtn" class="option selected" onclick="velgVisning('grid')"><span class="material-icons pil">grid_view</span></div>
            <div id="listeBtn" class="option" onclick="velgVisning('liste')"><span class="material-icons pil">list</span></div>
        </div>
    </div>   
</div>

<!-- Filter-popup -->
<div id="filterPopup" class="filter-modal hidden">
  <div class="filter-wrapper">
    <form id="filterForm">
      <div class="filter-grid">
        <input type="text" name="fornavn" placeholder="Fornavn">
        <input type="text" name="etternavn" placeholder="Etternavn">
        <input type="text" name="epost" placeholder="E-post">
        <input type="text" name="telefon" placeholder="Telefon (8 siffer)">
        <input type="text" name="adresse1" placeholder="Adresse 1">
        <input type="text" name="adresse2" placeholder="Adresse 2">

        <div class="postnr-range">
          <input type="text" name="postnrMin" placeholder="Postnr fra">
          <span>–</span>
          <input type="text" name="postnrMax" placeholder="Postnr til">
        </div>

        <input type="text" name="sted" placeholder="Sted">
        <input type="text" name="kommentar" placeholder="Kommentar">
      </div>

      <div class="filter-actions">
        <button type="button" id="lukkFilter">Avbryt</button>
        <button type="reset">Nullstill</button>
        <button type="submit" class="sok">Søk</button>
      </div>
    </form>
  </div>
</div>

<!-- Innholdsområde -->
<div class="container">
  <!-- Tabellvisning -->
  <table class="kunde-tabell" id="privatkunde-tabell" style="display: none;">
    <thead>
      <tr>
        <th>Fornavn</th>
        <th>Etternavn</th>
        <th>Epost</th>
        <th>Telefonnr</th>
        <th>Adresse1</th>
        <th>Adresse2</th>
        <th>Postnummer</th>
        <th>Sted</th>
      </tr>
    </thead>
    <tbody id="privatkunde-tbody">
      <!-- JS fyller inn <tr> her -->
    </tbody>
  </table>

  <!-- Grid-visning -->
  <div id="privatkunde-grid" class="kundeprofil-grid"></div>
</div>

<!-- Modal for detaljert profilvisning -->
<div id="profilModal" class="modal hidden">
    <div class="modal-content">
      <span class="close" onclick="lukkModal()">&times;</span>
      <div id="modalInnhold"></div>
    </div>
</div>

<!-- Hovedscript -->
<script src="privatkunde_liste.js"></script>
</body>
</html>
