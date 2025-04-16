<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>borettslag_liste</title>
    <link rel="stylesheet" href="../css/liste.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">   
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="../redirectToPage.js"></script> 
</head>

<body>
<div id="header">
  <?php include("../header/header.php"); ?>
</div>

<div class="headline-container">
<div class="dropdown">
            <button class="dropdown-btn" id="kundegruppeBtn">     
                BORETTSLAG <span class="material-symbols-outlined pil">arrow_drop_down</span>
            </button>
            <div class="dropdown-content">
                <a href="#" onclick="redirectToPage('liste_privatkunde/privatkunde_liste.php')">Privatkunder</a>
                <a href="#" onclick="redirectToPage('liste_bedriftkunde/bedriftkunde_liste.php')">Bedriftskunder</a>
            </div>
        </div>

    <div class="button-container">
        <a href="#" onclick="redirectToPage('registrer_borettslag/registrer_borettslaghtml.php')">
            <button class="secondaryBTN" id="nyKundeBtn">
                <span class="material-icons pil">add</span>
            </button>
        </a>
        <button id="exportBtn" onclick="exportToCSV()" class="secondaryBTN">
            <span class="material-icons pil">download</span> CSV
        </button>    
    </div>
</div>

<div class="sticky-header">
    <div class="visning-sok-wrapper">    
        <div class="search-container">
            <input type="text" id="sokefelt" placeholder="Søk..." oninput="filtrerKunder()"> 
            <button class="secondaryBTN" id="filter">
                <span class="material-icons pil">filter_alt</span>            
            </button>
        </div>    
    
        <div class="toggle">
            <div id="gridBtn" class="option selected" onclick="velgVisning('grid')"><span class="material-icons pil">grid_view</span></div>
            <div id="listeBtn" class="option" onclick="velgVisning('liste')"><span class="material-icons pil">list</span></div>
        </div>

    </div>   
</div>

<div id="filterPopup" class="filter-modal hidden">
  <div class="filter-wrapper">
    <form id="filterForm">
      <div class="filter-grid">
        <input type="text" name="navn" placeholder="Navn borettslag">
        <input type="text" name="adresse" placeholder="Adresse">
        <div class="postnr-range">
            <input type="text" name="postnrMin" placeholder="Postnr fra">
            <span>–</span>
            <input type="text" name="postnrMax" placeholder="Postnr til">
        </div>

        <input type="text" name="sted" placeholder="Sted">
        <input type="text" name="styreleder" placeholder="Styreleder">
        <input type="text" name="styrelederTlf" placeholder="Tlf styreleder">
        <input type="text" name="epost" placeholder="E-post">
        <input type="text" name="kontaktperson" placeholder="Kontaktperson">
        <input type="text" name="kontaktpersonTlf" placeholder="Tlf kontaktperson">
      </div>
      <div class="filter-actions">
        <button type="button" id="lukkFilter">Avbryt</button>
        <button type="reset">Nullstill</button>
        <button type="submit" class="sok">Søk</button>
      </div>
    </form>
  </div>
</div>


<div class="container">   
    <!-- Tabellvisning -->
    <table class="kunde-tabell" id="borettslag-tabell" style="display: none;">
        <thead>
            <tr>
                <th>Borettslag</th>
                <th>Adresse</th>
                <th>Postnummer</th>
                <th>Sted</th>
            </tr>
        </thead>
        <tbody id="borettslag-tbody">
            <!-- Fylles via JS -->
        </tbody>
    </table>

    <!-- Grid-visning -->
    <div id="borettslag-grid" class="kundeprofil-grid"></div>

</div>

<div id="profilModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" onclick="lukkModal()">&times;</span>
        <div id="modalInnhold"></div>
    </div>
</div>
<script src="borettslag_liste.js"></script>
</body>
</html>
