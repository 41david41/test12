<?php
include("../db2.php"); // Inkluderer tilkoblingen til MySQL-databasen

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

// Funksjon for å hente brukerens rolle (admin eller bruker)
function getUserRole($username, $pdo) {
    try {
        $sql = sprintf("SHOW GRANTS FOR '%s'", $username);
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

    return 'Bruker'; // Hvis ikke, er brukeren vanlig bruker
}

// Hent brukere fra user_details-tabellen med spesifikke felter
try {
    $sql = "SELECT fornavn, etternavn, user AS brukernavn, telefon, epost FROM user_details"; // Hent epost
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Feil ved henting av brukerinformasjon: " . $e->getMessage());
}

// Slette bruker
if (isset($_GET['delete_user'])) {
    $userToDelete = $_GET['delete_user'];

    try {
        // Start en transaksjon
        $pdo->beginTransaction();

        // Slett brukeren fra MySQL 'user_details'-tabellen
        $sqlDeleteUser = "DELETE FROM user_details WHERE brukernavn = :brukernavn";
        $stmtDeleteUser = $pdo->prepare($sqlDeleteUser);
        $stmtDeleteUser->bindParam(':brukernavn', $userToDelete, PDO::PARAM_STR);
        $stmtDeleteUser->execute();

        // Slett MySQL-brukeren fra mysql.brukernavn
        $sqlDeleteMySQLUser = "DROP USER :brukernavn@'%'";  // Bruker 'localhost' som standard, kan tilpasses
        $stmtDeleteMySQLUser = $pdo->prepare($sqlDeleteMySQLUser);
        $stmtDeleteMySQLUser->bindParam(':brukernavn', $userToDelete, PDO::PARAM_STR);
        $stmtDeleteMySQLUser->execute();

        // Etter sletting, omdiriger til brukeroversikt.php
        header("Location: brukeroversikt.php");

        // Fullfør transaksjonen
        $pdo->commit();
        
        exit(); // Husk å avslutte skriptet for å sikre at omdirigeringen skjer

    } catch (PDOException $e) {
        // Hvis noe går galt, rull tilbake transaksjonen
        $pdo->rollBack();
        echo "Feil ved sletting av bruker: " . $e->getMessage();
    }
}
?>