<?php
include("../db2.php");

function getUserRole($brukernavn, $pdo) {
    try {
        $sql = sprintf("SHOW GRANTS FOR '%s'", $brukernavn);
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gammeltBrukernavn = $_POST['gammelt_brukernavn']; // skjult input i skjema
    $nyttBrukernavn = $_POST['brukernavn'];
    $fornavn = $_POST['fornavn'];
    $etternavn = $_POST['etternavn'];
    $telefon = $_POST['telefon'];
    $epost = $_POST['epost'];
    $isAdmin = isset($_POST['adminrettigheter']) ? 1 : 0;

    try {
        $pdo->beginTransaction();

        // Sjekk at brukeren finnes
        $stmt = $pdo->prepare("SELECT user FROM user_details WHERE user = ?");
        $stmt->execute([$gammeltBrukernavn]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            die("❌ Fant ikke bruker med brukernavn '$gammeltBrukernavn'.");
        }

        // Oppdater informasjon
        $stmt = $pdo->prepare("UPDATE user_details SET user = ?, fornavn = ?, etternavn = ?, telefon = ?, epost = ? WHERE user = ?");
        $stmt->execute([$nyttBrukernavn, $fornavn, $etternavn, $telefon, $epost, $gammeltBrukernavn]);

        // Endre MySQL-brukernavn hvis det har endret seg
        if ($nyttBrukernavn !== $gammeltBrukernavn) {
            $stmt = $pdo->prepare("RENAME USER '$gammeltBrukernavn'@'%' TO '$nyttBrukernavn'@'%'");
            $stmt->execute();
            $pdo->exec("FLUSH PRIVILEGES");
        }

        // Admin-rolle
        $currentRole = getUserRole($gammeltBrukernavn, $pdo);
        if ($isAdmin && $currentRole !== 'Admin') {
            $pdo->exec("GRANT `adminbruker` TO '$nyttBrukernavn'@'%'");
        } elseif (!$isAdmin && $currentRole === 'Admin') {
            $pdo->exec("REVOKE `adminbruker` FROM '$nyttBrukernavn'@'%'");
        }

        $pdo->commit();
        header("Location: brukeroversikt.php");
        exit();
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo "Feil ved oppdatering: " . $e->getMessage();
    }
}

?>
