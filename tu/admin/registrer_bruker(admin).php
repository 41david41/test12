<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nybruker_admin</title>
    <link rel="stylesheet" href="../css/registrer.css">
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
    <h1 class="text-3xl font-light headline-left">Registrer ny bruker</h1>
  </div>

  <form action="registrer_bruker.php" method="POST" enctype="multipart/form-data" id="bedriftForm">
    <input type="file" id="imageUpload" name="profilbilde" accept="image/*" hidden>
    <div class="container">         
            <div class="form-container">   
            <div class="form-group-admin">
            <div class="form-group"><input type="text" name="brukernavn" placeholder="brukernavn maks 16" required></div>
                <div class="form-group"><input type="text" name="fornavn" placeholder="Fornavn" required></div>
                <div class="form-group"><input type="text" name="etternavn" placeholder="Etternavn" required></div>
                <div class="form-group"><input type="text" name="telefon" placeholder="Telefonnummer" pattern="^[0-9]{8}$"  required></div>
                <div class="form-group"><input type="email" name="epost" placeholder="E-post" required></div>
            </div>

            <div class="form-group-admin">
                <div class="form-group"><input type="email" name="bekreft_epost" placeholder="Bekreft E-post" required></div>
                <div class="form-group"><input type="password" name="passord" placeholder="Passord minst 6 tegn" required></div>
                <div class="form-group"><input type="password" name="bekreft_passord" placeholder="Bekreft passord" required></div>
                <div class="button-container">
                    <button class="secondaryBTN" type="reset">Nullstill</button>
                    <button id="profilbilde" class="secondaryBTN" type="button" onclick="document.getElementById('imageUpload').click();">➕ Profilbilde</button>
                    <button class="primaryBTN" type="submit">Registrer</button>
            </div>
            </div>    
        </div>      
    </div>
    <div id="bildePreview" class="preview-container"></div>
    </form>
    <script src="../preview.js"></script>
</body>
</html>