<?php
require_once("../db2.php");

// 🔎 Valideringsfunksjoner
function validateInput($data, $pattern) {
    return preg_match($pattern, $data);
}

function sanitize($data) {
    return htmlspecialchars(trim($data));
}

// 🧪 Regex-mønstre for inputvalidering
$patterns = [
    "fornavn"           => "/^[\p{L}\-]{2,}$/u",
    "etternavn"         => "/^[\p{L}\-]{2,}$/u",
    "brukernavn"        => "/^[a-zA-Z0-9_-]{3,16}$/",
    "epost"             => "/^[^@\s]+@[^@\s]+\.[^@\s]+$/",
    "bekreft_epost"     => "/^[^@\s]+@[^@\s]+\.[^@\s]+$/",
    "telefon"           => "/^\d{8}$/",
    "passord"           => "/^.{6,}$/",
    "bekreft_passord"   => "/^.{6,}$/"
];

$errors = [];
$data = [];

// ✅ Valider og saniter alle felt
foreach ($patterns as $key => $pattern) {
    if (!isset($_POST[$key]) || !validateInput($_POST[$key], $pattern)) {
        $errors[] = "$key har ugyldig eller manglende verdi.";
    } else {
        $data[$key] = sanitize($_POST[$key]);
    }
}

// 📧 Sammenlign e-post og passord-feltene
if ($_POST['epost'] !== $_POST['bekreft_epost']) {
    $errors[] = "E-postadressene er ikke like.";
}

if ($_POST['passord'] !== $_POST['bekreft_passord']) {
    $errors[] = "Passordene er ikke like.";
}

// ❌ Ved valideringsfeil, gi beskjed og stopp
if (!empty($errors)) {
    echo "<script>alert('" . implode("\\n", $errors) . "'); window.location.href='registrer_bruker(admin).php';</script>";
    exit;
}

// 🔐 Lagre passord og brukernavn
$passord = $_POST['passord'];
$brukernavn = $data['brukernavn'];

try {
    $pdo->beginTransaction();

    // Sjekk om brukeren finnes fra før
    $stmtCheck = $pdo->prepare("SELECT User FROM mysql.user WHERE User = :brukernavn AND Host = '%'");
    $stmtCheck->execute([':brukernavn' => $brukernavn]);
    $userExists = $stmtCheck->fetchColumn();

    if ($userExists) {
        die("<script>alert('Brukeren \"$brukernavn\" finnes allerede.'); window.location.href='../admin/brukeroversikt.php';</script>");
    }

    // Opprett MySQL-bruker
    $createUserSQL = "CREATE USER '$brukernavn'@'%' IDENTIFIED BY :passord";
    $stmt_user = $pdo->prepare($createUserSQL);
    $stmt_user->execute([':passord' => $passord]);

    // Gi brukeren alle rettigheter
    $grantSQL = "GRANT ALL PRIVILEGES ON *.* TO '$brukernavn'@'%' WITH GRANT OPTION";
    $pdo->exec($grantSQL);

    // Gi adminrolle dersom valgt
    if (isset($_POST['adminrettigheter']) && $_POST['adminrettigheter'] == "1") {
        $grantAdminSQL = "GRANT 'adminbruker' TO '$brukernavn'@'%'";
        $pdo->exec($grantAdminSQL);
    }

    $pdo->exec("FLUSH PRIVILEGES");

    // Sett inn brukerinfo i `user_details`
    $stmt_details = $pdo->prepare("
        INSERT INTO user_details (fornavn, etternavn, user, telefon, epost)
        VALUES (:fornavn, :etternavn, :brukernavn, :telefon, :epost)
    ");
    $stmt_details->execute([
        ':fornavn'     => $data['fornavn'],
        ':etternavn'   => $data['etternavn'],
        ':brukernavn'  => $brukernavn,
        ':telefon'     => $data['telefon'],
        ':epost'       => $data['epost']
    ]);

    // Ferdig – send brukeren tilbake til oversikten
    echo "<script>window.location.href = '../admin/brukeroversikt.php';</script>";
    fastcgi_finish_request(); // Avslutt PHP-prosess tidlig

    $pdo->commit();

} catch (PDOException $e) {
    $pdo->rollBack();
    echo "<script>alert('Databasefeil: " . $e->getMessage() . "'); window.location.href='registrer_bruker(admin).php';</script>";
    exit;
}
?>
