<?php
include("../db2.php"); // Tilkobling til MySQL-databasen

// Hent brukernavn fra session
$username = $_SESSION['db_username'];
$isAdmin = false;

// 🔒 Sjekk om brukeren er administrator
try {
    $sql = sprintf("SHOW GRANTS FOR '%s'", $username);
    $stmt = $pdo->query($sql);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $grantString = implode(" ", $row);

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

// 🔍 Funksjon for å hente en brukers rolle
function getUserRole($username, $pdo) {
    try {
        $sql = sprintf("SHOW GRANTS FOR '%s'", $username);
        $stmt = $pdo->query($sql);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $grantString = implode(" ", $row);
            if (stripos($grantString, "`adminbruker`") !== false) {
                return 'Admin';
            }
        }
    } catch (PDOException $e) {
        error_log("Feil ved sjekking av brukerrettigheter: " . $e->getMessage());
    }

    return 'Bruker';
}

// 📄 Hent alle brukere fra `user_details`-tabellen
try {
    $sql = "SELECT fornavn, etternavn, user AS brukernavn, telefon, epost FROM user_details";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Feil ved henting av brukerinformasjon: " . $e->getMessage());
}

// ❌ Sletting av bruker hvis ?delete_user er satt i URL
if (isset($_GET['delete_user'])) {
    $userToDelete = $_GET['delete_user'];

    try {
        $pdo->beginTransaction();

        // Slett bruker fra `user_details`
        $sqlDeleteUser = "DELETE FROM user_details WHERE brukernavn = :brukernavn";
        $stmtDeleteUser = $pdo->prepare($sqlDeleteUser);
        $stmtDeleteUser->bindParam(':brukernavn', $userToDelete, PDO::PARAM_STR);
        $stmtDeleteUser->execute();

        // Slett selve MySQL-brukeren
        $sqlDeleteMySQLUser = "DROP USER :brukernavn@'%'";
        $stmtDeleteMySQLUser = $pdo->prepare($sqlDeleteMySQLUser);
        $stmtDeleteMySQLUser->bindParam(':brukernavn', $userToDelete, PDO::PARAM_STR);
        $stmtDeleteMySQLUser->execute();

        $pdo->commit();

        // Omdiriger etter sletting
        header("Location: brukeroversikt.php");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Feil ved sletting av bruker: " . $e->getMessage();
    }
}
?>
