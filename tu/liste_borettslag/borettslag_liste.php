<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>borettslag_liste</title>
    <link rel="stylesheet" href="../css/liste.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down,arrow_drop_up" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="../redirectToPage.js"></script>
    <script src="borettslag_liste.js"></script>
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
            <h1 class="text-3xl font-light headline-left">Kundegruppe</h1>
        
          <div class="button-container">
            <div class="dropdown">
                <button class="dropdown-btn" id="kundegruppeBtn">Borettslag </button>
                  <div class="dropdown-content">
                    <a href="#" onclick="redirectToPage('liste_privatkunde/privatkunde_liste.php')">Privatkunde</a>
                    <a href="#" onclick="redirectToPage('liste_bedriftkunde/bedriftkunde_liste.php')">Bedriftskunde</a>
                </div>
            </div>
            <a href="#" onclick="redirectToPage('registrer_borettslag/registrer_borettslag.html')"><button class="pluss-btn">➕</button></a>
          </div>
    </div>
 
    
    <div class="container">
      <!--<div class="view-options">
            <button class="grid-view-button">🔲</button>
            <button class="list-view-button">☰</button>
            <button class="measure-button">📏</button>
        </div>-->
    
        <div id="borettslag-tabell" class="kundeprofil-grid"></div>

    </div>

    <div id="profilModal" class="modal hidden">
        <div class="modal-content">
          <span class="close" onclick="lukkModal()">&times;</span>
          <div id="modalInnhold"></div>
        </div>
      </div>
      
</body>
</html>


  