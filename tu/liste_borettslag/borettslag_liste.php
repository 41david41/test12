<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>borettslag_liste</title>
    <link rel="stylesheet" href="../css/liste.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down" />
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
    

    <div class="headline-container">
            <h1 class="text-3xl font-light headline-left">Borettslag</h1>
        
          <div class="button-container">
            <div class="dropdown">
                <button class="dropdown-btn" id="kundegruppeBtn">Bytt kundetype<span class="material-symbols-outlined pil">arrow_drop_down</span> </button>
                  <div class="dropdown-content">
                    <a href="#" onclick="redirectToPage('liste_privatkunde/privatkunde_liste.php')">Privatkunder</a>
                    <a href="#" onclick="redirectToPage('liste_bedriftkunde/bedriftkunde_liste.php')">Bedriftskunder</a>
                </div>
            </div>
            <a href="#" onclick="redirectToPage('registrer_borettslag/registrer_borettslag.html')"><button class="pluss-btn">➕</button></a>
          </div>
    </div>
 
    
    <div class="container">
      <div class="visning-sok-wrapper">
        <div class="left-spacer"></div> <!-- tom plass på venstre side -->
        
        <div class="view-options">
          <button onclick="settVisning('grid')">🔲</button>
          <button onclick="settVisning('liste')">☰</button>
        </div>
      
        <div class="search-container">
          <input type="text" id="sokefelt" placeholder="Søk..." oninput="filtrerKunder()">
        </div>
      </div>
      
    
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


  