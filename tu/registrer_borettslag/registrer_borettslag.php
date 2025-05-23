<?php
require_once("../db.php"); // Koble til databasen

// Funksjon for enkel regex-validering
function validateInput($data, $pattern) {
    return preg_match($pattern, $data);
}

// Funksjon for å rense inputdata
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

// Definerer regex-mønstre for alle felt
$patterns = [
    "orgnr" => "/^\d{9}$/",
    "navn" => "/^[\p{L}0-9\s\-\.]{2,}$/u",
    "styreleder" => "/^[\p{L}\s\-]{2,}$/u",
    "adresse1" => "/^.{2,}$/",
    "adresse2" => "/^.{0,}$/",
    "postnr" => "/^\d{4}$/",
    "sted" => "/^[\p{L}\s\-]{2,}$/u",
    "epost" => "/^[^@\s]+@[^@\s]+\.[^@\s]+$/",
    "telefon" => "/^\d{8}$/",
    "kontaktperson" => "/^[\p{L}\s\-]{2,}$/u",
    "kontaktpersonTlf" => "/^\d{8}$/",
    "kommentar" => "/.*/"
];

$errors = [];
$data = [];

// Valider og rens inputdata
foreach ($patterns as $key => $pattern) {
    if (!isset($_POST[$key]) || !validateInput($_POST[$key], $pattern)) {
        $errors[] = "$key har ugyldig eller manglende verdi.";
    } else {
        $data[$key] = sanitize($_POST[$key]);
    }
}

// Hvis feil, vis alert og gå tilbake til skjema
if (!empty($errors)) {
    echo "<script>alert('" . implode("\n", $errors) . "'); window.location.href='registrer_borettslaghtml.php';</script>";
    exit;
}

// Sjekk om orgnr eller e-post allerede finnes i databasen
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM borettslagkunde WHERE orgnr = :orgnr OR epost = :epost");
    $stmt->execute([
        ':orgnr' => $data['orgnr'],
        ':epost' => $data['epost']
    ]);
    $existingCount = $stmt->fetchColumn();

    if ($existingCount > 0) {
        echo "<script>alert('Organisasjonsnummer eller e-post er allerede registrert.'); window.location.href='registrer_borettslaghtml.php';</script>";
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Databasefeil: " . $e->getMessage()]);
    exit;
}

// Håndtering av bildeopplasting
$bildePath = "";
if (isset($_FILES['bilde']) && $_FILES['bilde']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($_FILES['bilde']['type'], $allowedTypes)) {
        $ext = pathinfo($_FILES['bilde']['name'], PATHINFO_EXTENSION);
        $newName = uniqid("bilde_", true) . '.' . $ext;
        $uploadPath = "../liste_borettslag/Uploads/" . $newName;
        move_uploaded_file($_FILES['bilde']['tmp_name'], $uploadPath);
        $bildePath = $uploadPath;
    }
}

// Håndtering av PDF-opplasting
$pdfPath = "";
if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
    if ($_FILES['pdf']['type'] === 'application/pdf') {
        $ext = pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION);
        $newName = uniqid("pdf_", true) . '.' . $ext;
        $uploadPath = "../liste_borettslag/Uploads/" . $newName;
        move_uploaded_file($_FILES['pdf']['tmp_name'], $uploadPath);
        $pdfPath = $uploadPath;
    }
}

// Sett inn data i databasen
try {
    $stmt = $pdo->prepare("INSERT INTO borettslagkunde (
        orgnr, navn, styreleder, adresse1, adresse2, postnr, sted, epost, telefon, kontaktperson, kontaktpersonTlf, kommentar, bilde, pdf
    ) VALUES (
        :orgnr, :navn, :styreleder, :adresse1, :adresse2, :postnr, :sted, :epost, :telefon, :kontaktperson, :kontaktpersonTlf, :kommentar, :bilde, :pdf
    )");

    $stmt->execute([
        ':orgnr' => $data['orgnr'],
        ':navn' => $data['navn'],
        ':styreleder' => $data['styreleder'],
        ':adresse1' => $data['adresse1'],
        ':adresse2' => $data['adresse2'],
        ':postnr' => $data['postnr'],
        ':sted' => $data['sted'],
        ':epost' => $data['epost'],
        ':telefon' => $data['telefon'],
        ':kontaktperson' => $data['kontaktperson'],
        ':kontaktpersonTlf' => $data['kontaktpersonTlf'],
        ':kommentar' => $data['kommentar'],
        ':bilde' => $bildePath,
        ':pdf' => $pdfPath
    ]);

    // Send bruker tilbake til oversiktssiden ved suksess
    header("Location: ../liste_borettslag/borettslag_liste.php"); 

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Databasefeil: " . $e->getMessage()]);
}
?>
