<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registrer_privatkunde</title>
    <link rel="stylesheet" href="../css/registrer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="../redirectToPage.js"></script>
    <script src="../header/include.js"></script>
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
    <h1 class="text-3xl font-light headline-left">PRIVATKUNDE</h1>

  
    <div class="dropdown">
        <button class="dropdown-btn" id="kundegruppeBtn"><span class="material-symbols-outlined pil">arrow_drop_down</span></button>
        <div class="dropdown-content">
            <a href="#" onclick="redirectToPage('registrer_borettslag/registrer_borettslaghtml.php')">Borettslag</a>
            <a href="#" onclick="redirectToPage('registrer_bedriftkunde/registrer_bedriftkundehtml.php')">Bedriftskunde</a>
        </div>
    </div>
</div>

<form method="POST" enctype="multipart/form-data" id="bedriftForm">
<div class="container">
    <div class="form-container">    
        
        <div class="form-left">
            <div class="form-group"><input name="fornavn" type="text" placeholder="Fornavn" required></div>
            <div class="form-group"><input name="etternavn" type="text" placeholder="Etternavn" required></div>
            <div class="form-group"><input name="epost" type="text" placeholder="E-post" required></div>
            <div class="form-group"><input name="telefon" type="text" placeholder="Telefonnummer" pattern="^[0-9]{8}$" required></div>
            <div class="form-group"><input class="invisble" type="text"></div>
        </div>

        <div class="form-middel">
            <div class="form-group"><input name="adresse1" type="text" placeholder="Adresse 1" required></div>
            <div class="form-group"><input name="adresse2" type="text" placeholder="Adresse 2"></div>
            <div class="split-input">
                <div class="form-group half-width"><input name="postnr" type="text" placeholder="PostNr." pattern="^[0-9]{4}$" required></div>
                <div class="form-group half-width"><input name="sted" type="text" placeholder="Sted" required></div>
            </div>
            
            <div class="form-group"><input class="invisble" type="text"></div>
            <div class="form-group"><input class="invisble" type="text"></div>
            
        </div>

        <div class="form-right">
            <div class="form-group"><textarea name="kommentar" placeholder="Kommentar"></textarea></div>
            
            <div class="button-container">
                <input type="file" name="bilde" id="imageUpload" accept="image/*" hidden>
                <button id="bilde" class="secondaryBTN" type="button" onclick="document.getElementById('imageUpload').click();">
                    <span class="material-icons">image</span>
                </button>
           
                <input type="file" name="pdf" id="pdfUpload" accept="application/pdf" hidden>
                <button id="PDF" class="secondaryBTN" type="button" onclick="document.getElementById('pdfUpload').click();">
                    <span class="material-icons">picture_as_pdf</span>
                </button>
            </div>
            
              <div class="button-container">
                <button type="submit" class="primaryBTN">Registrer</button>
              </div>
            </div>  
          </div>
        </div>
        <div id="bildePreview" class="preview-container"></div>
        <div id="pdfPreview" class="preview-container"></div>
      </form>
      <script src="../preview.js"></script>
      <script src="registrer_privatkunde.js"></script>

</body>
</html>