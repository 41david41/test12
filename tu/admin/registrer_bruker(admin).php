<?php
include("../db2.php");

$erRedigering = false;
$brukerdata = [
    'user' => '',
    'epost' => '',
    'fornavn' => '',
    'etternavn' => '',
    'telefon' => ''
];

// 🔄 Hent eksisterende brukerdata hvis vi er i redigeringsmodus
if (isset($_GET['brukernavn'])) {
    $erRedigering = true;
    $brukernavn = $_GET['brukernavn'];

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

    <!-- Fonter og ikoner -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Eget stilark -->
    <link rel="stylesheet" href="../css/registrer.css">

    <!-- JavaScript -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="../redirectToPage.js"></script>
</head>
<body>

<!-- Header-område -->
<div id="header">
    <?php include("../header/header.php"); ?>
</div>

<!-- Overskrift -->
<div class="headline-container">
    <h1 class="text-3xl font-light headline-left">
        <?php echo $erRedigering ? "OPPDATER BRUKER" : "NY BRUKER"; ?>
    </h1>
</div>

<!-- Brukerregistrerings- eller redigeringsskjema -->
<form action="rediger_bruker_backend.php" method="POST" enctype="multipart/form-data" id="bedriftForm">

    <!-- Skjult felt for redigering -->
    <?php if ($erRedigering): ?>
        <input type="hidden" name="gammelt_brukernavn" value="<?php echo htmlspecialchars($brukerdata['user']); ?>">
    <?php endif; ?>

    <!-- Skjult bildeopplaster -->
    <input type="file" id="imageUpload" name="profilbilde" accept="image/*" hidden>

    <div class="container">
        <div class="form-container">

            <!-- Venstre kolonne -->
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

            <!-- Høyre kolonne -->
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

                <!-- Admin-rettighetsvalg -->
                <div class="checkbox-admin">
                    <h2>Adminrettigheter</h2> 
                    <input id="checkbox" type="checkbox" name="adminrettigheter" value="1" <?php echo $erRedigering ? 'checked' : ''; ?>> 
                </div>

                <!-- Handlingsknapper -->
                <div class="button-container">
                    <button class="secondaryBTN" type="reset">
                        <span class="material-icons pil">undo</span>
                    </button>
                    <button id="profilbilde" class="secondaryBTN" type="button"
                        onclick="document.getElementById('imageUpload').click();">
                        <span class="material-icons pil">account_circle</span>
                    </button>
                    <button class="primaryBTN" type="submit">
                        <?php echo $erRedigering ? 'Lagre endringer' : 'Registrer'; ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Forhåndsvisning av profilbilde -->
    <div id="bildePreview" class="preview-container"></div>
</form>

<!-- JS for forhåndsvisning av bilde -->
<script src="../preview.js"></script>
</body>
</html>
