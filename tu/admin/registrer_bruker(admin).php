<?php
include("../db2.php");

$erRedigering = false;
$brukerdata = [
    'user' => '',
    'epost' => '',
    'fornavn' => '',
    'etternavn' => '',
    'telefon' => '',
    // legg til flere felter her hvis det trengs
];

// Sjekk om vi skal redigere
if (isset($_GET['brukernavn'])) {
    $erRedigering = true;
    $brukernavn = $_GET['brukernavn'];  // Ikke bruk intval

    $stmt = $pdo->prepare("SELECT * FROM user_details WHERE user = ?");
    $stmt->execute([$brukernavn]);
    $data = array_change_key_case($stmt->fetch(PDO::FETCH_ASSOC), CASE_LOWER);

    if ($data) {
        $brukerdata = array_merge($brukerdata, $data);
    } else {
        die("❌ Fant ikke bruker med brukernavn '$brukernavn'.");
    }
}
?>


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
    <h1 class="text-3xl font-light headline-left"><?php echo $erRedigering ? "OPPDATER BRUKER" : "NY BRUKER"; ?></h1>
  </div>

  <form action="rediger_bruker_backend.php" method="POST" enctype="multipart/form-data" id="bedriftForm">
    <?php if ($erRedigering): ?>
        <input type="hidden" name="gammelt_brukernavn" value="<?php echo htmlspecialchars($brukerdata['user']); ?>">
    <?php endif; ?>
    <input type="file" id="imageUpload" name="profilbilde" accept="image/*" hidden>
    <div class="container">         
  <div class="form-container">   
    <div class="form-group-admin">

      <div class="form-group">
        <input type="text" name="brukernavn"
          value="<?php echo htmlspecialchars($brukerdata['user']); ?>"
          placeholder="Fyll inn brukernavn" required>
      </div>

      <div class="form-group">
        <input type="text" name="fornavn"
          value="<?php echo htmlspecialchars($brukerdata['fornavn']); ?>"
          placeholder="Fornavn" required>
      </div>

      <div class="form-group">
        <input type="text" name="etternavn"
          value="<?php echo htmlspecialchars($brukerdata['etternavn']); ?>"
          placeholder="Etternavn" required>
      </div>

      <div class="form-group">
        <input type="text" name="telefon"
          value="<?php echo htmlspecialchars($brukerdata['telefon']); ?>"
          placeholder="Telefonnummer" pattern="^[0-9]{8}$" required>
      </div>

      <div class="form-group">
        <input type="email" name="epost"
          value="<?php echo htmlspecialchars($brukerdata['epost']); ?>"
          placeholder="E-post" required>
      </div>

    </div>

    <div class="form-group-admin">
      <div class="form-group">
        <input type="email" name="bekreft_epost"
        value="<?php echo htmlspecialchars($brukerdata['epost']); ?>"
          placeholder="Bekreft E-post" required>
      </div>

      <div class="form-group">
        <input type="password" name="passord"
          placeholder="Passord minst 6 tegn" <?php echo $erRedigering ? '' : 'required'; ?>>
      </div>

      <div class="form-group">
        <input type="password" name="bekreft_passord"
          placeholder="Bekreft passord" <?php echo $erRedigering ? '' : 'required'; ?>>
      </div>

      <div class="checkbox-admin">
  <input type="checkbox" name="adminrettigheter" value="1"
    <?php echo $erRedigering ? 'checked' : ''; ?>>
  <a>Adminrettigheter</a>
</div>


      <div class="button-container">
        <button class="secondaryBTN" type="reset">Nullstill</button>
        <button id="profilbilde" class="secondaryBTN" type="button" onclick="document.getElementById('imageUpload').click();">➕ Profilbilde</button>
        <button class="primaryBTN" type="submit"><?php echo $erRedigering ? 'Lagre endringer' : 'Registrer'; ?></button>
      </div>
    </div>    
  </div>      
</div>

    <div id="bildePreview" class="preview-container"></div>
    </form>
    <script src="../preview.js"></script>
</body>
</html> 