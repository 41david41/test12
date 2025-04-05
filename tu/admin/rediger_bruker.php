<?php
include("../db2.php"); // Inkluderer tilkoblingen til MySQL-databasen

// Start session og sjekk om brukeren er logget inn som admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("❌ Ikke logget inn. Vennligst logg inn først.");
}

// Sjekk om brukeren er admin
$username = $_SESSION['db_username'];
$isAdmin = false;

// Hent brukerens rettigheter med SHOW GRANTS
try {
    $sql = sprintf("SHOW GRANTS FOR '%s'", $username);
    $stmt = $pdo->query($sql);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $grantString = implode(" ", $row);
        // Sjekk om brukeren er admin
        if (stripos($grantString, "`adminbruker`") !== false) {
            $isAdmin = true;
            break;
        }
    }

    if (!$isAdmin) {
        die("❌ Du har ikke tilstrekkelige rettigheter for å se denne siden.");
    }

} catch (PDOException $e) {
    echo "Feil ved henting av brukerrettigheter: " . $e->getMessage();
}

// Hent brukerens brukernavn som skal redigeres fra URL
if (isset($_GET['brukernavn'])) {
    $brukernavn = $_GET['brukernavn'];

    try {
        // Hent eksisterende informasjon fra user_details-tabellen
        $sql = "SELECT * FROM user_details WHERE user = :brukernavn";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':brukernavn', $brukernavn, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("❌ Brukeren ble ikke funnet.");
        }

    } catch (PDOException $e) {
        die("Feil ved henting av brukerdata: " . $e->getMessage());
    }
} else {
    die("❌ Ingen brukernavn angitt.");
}

// Oppdater brukerinformasjon hvis skjemaet er sendt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hent data fra skjemaet
    $newUsername = $_POST['brukernavn'];
    $newFirstName = $_POST['fornavn'];
    $newLastName = $_POST['etternavn'];

    try {
        // Start en transaksjon
        $pdo->beginTransaction();

        // 1. Slett den gamle brukeren fra user_details-tabellen
        $sqlDeleteOldUserDetails = "DELETE FROM user_details WHERE user = :brukernavn";
        $stmtDeleteOldUserDetails = $pdo->prepare($sqlDeleteOldUserDetails);
        $stmtDeleteOldUserDetails->bindParam(':brukernavn', $brukernavn, PDO::PARAM_STR);
        $stmtDeleteOldUserDetails->execute();

        // 2. Legg til den nye brukeren i user_details-tabellen
        $sqlInsertNewUserDetails = "INSERT INTO user_details (user, fornavn, etternavn, telefon, Created_At) 
                                    VALUES (:brukernavn, :fornavn, :etternavn, :telefon, NOW())";
        $stmtInsertNewUserDetails = $pdo->prepare($sqlInsertNewUserDetails);
        $stmtInsertNewUserDetails->bindParam(':brukernavn', $newUsername, PDO::PARAM_STR);
        $stmtInsertNewUserDetails->bindParam(':fornavn', $newFirstName, PDO::PARAM_STR);
        $stmtInsertNewUserDetails->bindParam(':etternavn', $newLastName, PDO::PARAM_STR);
        $stmtInsertNewUserDetails->bindParam(':telefon', $user['telefon'], PDO::PARAM_STR); // Beholder gammel telefon
        $stmtInsertNewUserDetails->execute();

        // Hvis brukernavnet er endret, oppdater det i MySQL-brukeren også
        if ($brukernavn !== $newUsername) {
            // 3. Bruk RENAME USER for å endre brukernavnet
            $sqlRenameUser = "RENAME USER :old_username@'%' TO :new_username@'%'";
            $stmtRenameUser = $pdo->prepare($sqlRenameUser);
            $stmtRenameUser->bindParam(':old_username', $brukernavn, PDO::PARAM_STR);
            $stmtRenameUser->bindParam(':new_username', $newUsername, PDO::PARAM_STR);
            $stmtRenameUser->execute();
        }

        // Omdiriger tilbake til brukeroversikt
        header("Location: ../admin/brukeroversikt.php");
        exit();
        
        // Fullfør transaksjonen
        $pdo->commit();

        

    } catch (PDOException $e) {
        // Hvis noe går galt, rull tilbake transaksjonen
        $pdo->rollBack();
        echo "Feil ved oppdatering av bruker: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rediger Bruker</title>
    <link rel="stylesheet" href="../css/liste.css">
</head>
<body>
    <div id="header">
        <?php include("../header/header.php"); ?>
    </div>

    <h1>Rediger Bruker</h1>

    <form action="rediger_bruker.php?brukernavn=<?php echo urlencode($brukernavn); ?>" method="POST">
        <label for="brukernavn">Brukernavn:</label>
        <input type="text" name="brukernavn" value="<?php echo htmlspecialchars($user['user']); ?>" required>

        <label for="fornavn">Fornavn:</label>
        <input type="text" name="fornavn" value="<?php echo htmlspecialchars($user['fornavn']); ?>" required>

        <label for="etternavn">Etternavn:</label>
        <input type="text" name="etternavn" value="<?php echo htmlspecialchars($user['etternavn']); ?>" required>

        <button type="submit">Oppdater</button>
    </form>
</body>
</html>
