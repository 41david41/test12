<?php
include("../db2.php"); // Inkluderer tilkoblingen til MySQL-databasen

// Start session og sjekk om brukeren er logget inn som admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("❌ Ikke logget inn. Vennligst logg inn først.");
}

// Hent brukerens data hvis brukernavn er spesifisert
if (isset($_GET['brukernavn'])) {
    $brukernavn = $_GET['brukernavn'];
    $stmt = $pdo->prepare("SELECT fornavn, etternavn, telefon, epost FROM user_details WHERE user = :brukernavn");
    $stmt->bindParam(':brukernavn', $brukernavn, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("❌ Bruker ikke funnet.");
    }

    // Sjekk om brukeren har rollen "adminbruker"
    function getUserRole($brukernavn, $pdo) {
        try {
            // Henter brukerens tillatelser
            $sql = sprintf("SHOW GRANTS FOR '%s'", $brukernavn);
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $grantString = implode(" ", $row);
                // Hvis grant-strengen inneholder 'adminbruker', så er brukeren admin
                if (stripos($grantString, "`adminbruker`") !== false) {
                    return 'Admin';
                }
            }
        } catch (PDOException $e) {
            error_log("Feil ved sjekking av brukerrettigheter: " . $e->getMessage());
        }

        // Hvis ikke, er brukeren vanlig bruker
        return 'Bruker';
    }

    // Hent brukerens rolle for brukeren som skal redigeres
    $userRole = getUserRole($brukernavn, $pdo);

    // Hvis brukeren er admin, skal checkboxen være markert
    $isAdminChecked = ($userRole === 'Admin') ? 'checked' : '';
}

// Oppdater brukerens informasjon
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Hent de nye verdiene fra skjemaet
    $fornavn = $_POST['fornavn'];
    $etternavn = $_POST['etternavn'];
    $telefon = $_POST['telefon'];
    $epost = $_POST['epost'];
    $nyttBrukernavn = $_POST['brukernavn'];  // Nytt brukernavn
    $isAdmin = isset($_POST['admin']) ? 1 : 0;  // Håndtere om brukeren er admin

    // Start en transaksjon
    $pdo->beginTransaction();

    try {
        // Først oppdaterer vi brukerens informasjon i user_details-tabellen
        $sql = "UPDATE user_details SET fornavn = :fornavn, etternavn = :etternavn, telefon = :telefon, epost = :epost WHERE user = :brukernavn";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fornavn', $fornavn, PDO::PARAM_STR);
        $stmt->bindParam(':etternavn', $etternavn, PDO::PARAM_STR);
        $stmt->bindParam(':telefon', $telefon, PDO::PARAM_STR);
        $stmt->bindParam(':epost', $epost, PDO::PARAM_STR);
        $stmt->bindParam(':brukernavn', $brukernavn, PDO::PARAM_STR);
        $stmt->execute();

        // Hvis brukernavnet er endret, oppdater mysql.user og user_details
        if ($nyttBrukernavn !== $brukernavn) {
            // Endre brukernavnet i mysql.user-tabellen
            $sqlRenameUser = "RENAME USER '$brukernavn'@'%' TO '$nyttBrukernavn'@'%'";
            $stmtRenameUser = $pdo->prepare($sqlRenameUser);
            $stmtRenameUser->execute();

            // Kjør FLUSH PRIVILEGES for å sikre at endringene i mysql.user trer i kraft
            $pdo->exec("FLUSH PRIVILEGES");

            // Nå oppdaterer vi brukernavnet også i user_details-tabellen for å matche det nye brukernavnet
            $sqlUpdateUserDetails = "UPDATE user_details SET user = :nyttBrukernavn WHERE user = :brukernavn";
            $stmtUpdateUserDetails = $pdo->prepare($sqlUpdateUserDetails);
            $stmtUpdateUserDetails->bindParam(':nyttBrukernavn', $nyttBrukernavn, PDO::PARAM_STR);
            $stmtUpdateUserDetails->bindParam(':brukernavn', $brukernavn, PDO::PARAM_STR);
            $stmtUpdateUserDetails->execute();
        }

        // Håndter admin-rettigheter basert på checkboxen
        if ($isAdmin) {
            // Hvis checkboxen er krysset av, sjekk om brukeren allerede har admin-rettigheter
            $currentRole = getUserRole($brukernavn, $pdo);
            if ($currentRole !== 'Admin') {
                // Hvis brukeren ikke har admin-rettigheter, tildel dem
                $sqlGrantAdmin = "GRANT `adminbruker` TO '$brukernavn'@'%'";
                $pdo->exec($sqlGrantAdmin);
            }
        } else {
            // Hvis checkboxen ikke er krysset av, fjern admin-rettigheter hvis de finnes
            $currentRole = getUserRole($brukernavn, $pdo);
            if ($currentRole === 'Admin') {
                // Fjern admin-rettigheter
                $sqlRevokeAdmin = "REVOKE `adminbruker` FROM '$brukernavn'@'%'";
                $pdo->exec($sqlRevokeAdmin);
            }
        }

        header("Location: brukeroversikt.php");
        // Fullfør transaksjonen
        $pdo->commit();

        // Etter vellykket oppdatering, omdiriger til brukeroversikt.php
        
        exit();
    } catch (PDOException $e) {
        // Hvis noe går galt, rull tilbake transaksjonen
        $pdo->rollBack();
        echo "Feil ved oppdatering: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rediger Bruker</title>
    <link rel="stylesheet" href="../css/registrer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
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
    <h1 class="text-3xl font-light headline-left">Rediger bruker</h1>
</div>

<form action="rediger_bruker.php?brukernavn=<?php echo htmlspecialchars($brukernavn); ?>" method="POST">
    <div class="container">         
        <div class="form-container">   
            <div class="form-group-admin">
                <div class="form-group"><input type="text" name="brukernavn" value="<?php echo htmlspecialchars($brukernavn); ?>" required></div>
                <div class="form-group"><input type="text" name="fornavn" value="<?php echo htmlspecialchars($user['fornavn']); ?>" required></div>
                <div class="form-group"><input type="text" name="etternavn" value="<?php echo htmlspecialchars($user['etternavn']); ?>" required></div>
                <div class="form-group"><input type="text" name="telefon" value="<?php echo htmlspecialchars($user['telefon']); ?>" pattern="^[0-9]{8}$" required></div>
                <div class="form-group"><input type="email" name="epost" value="<?php echo htmlspecialchars($user['epost']); ?>" required></div>
            </div>

            <div class="form-group-admin">
                <!-- Checkbox for Admin status -->
                <div class="form-group">
                    <label for="admin">Er admin:</label>
                    <input type="checkbox" name="admin" <?php echo $isAdminChecked; ?>>
                </div>
            </div>

            <div class="button-container">
                <button class="primaryBTN" type="submit">Oppdater</button>
            </div>
        </div>      
    </div>
</form>
</body>
</html>
