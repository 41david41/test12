<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bedriftskunde_liste</title>

    <!-- Stiler og fonter -->
    <link rel="stylesheet" href="../css/liste.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down" />

    <!-- JavaScript og Tailwind (browserversjon) -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="../redirectToPage.js"></script>  
</head>

<body>
<!-- Felles toppmeny/header -->
<div id="header">    
  <?php include("../header/header.php"); ?>
</div>

<!-- Overskrift og navigasjonsvalg -->
<div class="headline-container">
    <div class="dropdown">
        <!-- Valg av kundegruppe -->
        <button class="dropdown-btn" id="kundegruppeBtn">
            BEDRIFTSKUNDER <span class="material-symbols-outlined pil">arrow_drop_down</span>
        </button>
        <div class="dropdown-content">
            <a href="#" onclick="redirectToPage('liste_privatkunde/privatkunde_liste.php')">Privatkunder</a>
            <a href="#" onclick="redirectToPage('liste_borettslag/borettslag_liste.php')">Borettslag</a>
        </div>
    </div>

    <!-- Handlingsknapper -->
    <div class="button-container">
        <!-- Ny kunde -->
        <a href="#" onclick="redirectToPage('registrer_bedriftkunde/registrer_bedriftkundehtml.php')">
            <button class="secondaryBTN" id="nyKundeBtn">
                <span class="material-icons pil">add</span>
            </button>
        </a>

        <!-- Eksport til CSV -->
        <button id="exportBtn" onclick="exportToCSV()" class="secondaryBTN">
            <span class="material-icons pil">download</span> CSV
        </button>    
    </div>
</div>

<!-- Søke- og visningsalternativer -->
<div class="sticky-header">
    <div class="visning-sok-wrapper">    
        <!-- Søkeinput -->
        <div class="search-container">
            <input type="text" id="sokefelt" placeholder="Søk..." oninput="filtrerKunder()"> 
            <button class="secondaryBTN" id="filter">
                <span class="material-icons pil">filter_alt</span>            
            </button>
        </div>    

        <!-- Visningsvalg (grid/list) -->
        <div class="toggle">
            <div id="gridBtn" class="option selected" onclick="velgVisning('grid')">
                <span class="material-icons pil">grid_view</span>
            </div>
            <div id="listeBtn" class="option" onclick="velgVisning('liste')">
                <span class="material-icons pil">list</span>
            </div>
        </div>
    </div>   
</div>

<!-- Popup for avansert filtrering -->
<div id="filterPopup" class="filter-modal hidden">
  <div class="filter-wrapper">
    <form id="filterForm">
      <div class="filter-grid">
        <!-- Ulike filterfelt -->
        <input type="text" name="orgnr" placeholder="Organisasjonsnummer">
        <input type="text" name="bedriftsnavn" placeholder="Navn bedrift">
        <input type="text" name="adresse1" placeholder="Adresse 1">
        <input type="text" name="adresse2" placeholder="Adresse 2">

        <div class="postnr-range">
          <input type="text" name="postnrMin" placeholder="Postnr fra">
          <span>–</span>
          <input type="text" name="postnrMax" placeholder="Postnr til">
        </div>

        <input type="text" name="sted" placeholder="Sted">
        <input type="text" name="epost" placeholder="E-post">
        <input type="text" name="telefon" placeholder="Telefon">
        <input type="text" name="kontaktperson" placeholder="Kontaktperson">
        <input type="text" name="kontaktpersonTlf" placeholder="Tlf kontaktperson">
        <input type="text" name="kommentar" placeholder="Kommentar">
      </div>

      <!-- Handlingsknapper -->
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
    <!-- Tabellvisning av bedriftskunder -->
    <table class="kunde-tabell" id="bedriftkunde-tabell" style="display: none;">
        <thead>
            <tr>
                <th>Organisasjonsnummer</th>
                <th>Bedriftsnavn</th>
                <th>Adresse1</th>
                <th>Adresse2</th>
                <th>Postnr</th>
                <th>Sted</th>
                <th>Epost</th>
                <th>Kontaktperson</th>
                <th>kontaktpersontelefon</th>
            </tr>
        </thead>
        <tbody id="bedriftkunde-tbody">
            <!-- Fylles ut dynamisk av JavaScript -->
        </tbody>
    </table>

    <!-- Grid-visning av bedriftskunder -->
    <div id="bedriftkunde-grid" class="kundeprofil-grid"></div>
</div>

<!-- Modal for detaljert visning av kundeprofil -->
<div id="profilModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" onclick="lukkModal()">&times;</span>
        <div id="modalInnhold"></div>
    </div>
</div>

<!-- Scripts for funksjonalitet -->
<script src="../preview.js"></script>
<script src="bedriftkunde_liste.js"></script>

</body>
</html>
