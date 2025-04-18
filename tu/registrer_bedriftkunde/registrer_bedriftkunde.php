<?php
// Inkluderer databasekobling
require_once("../db.php");

// Sjekker at forespørselen er POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Henter data fra skjemaet
    $orgnr = $_POST["orgnr"];
    $bedriftsnavn = $_POST["bedriftsnavn"];
    $adresse1 = $_POST["adresse1"];
    $adresse2 = $_POST["adresse2"];
    $postnr = $_POST["postnr"];
    $sted = $_POST["sted"];
    $epost = $_POST["epost"];
    $kontaktperson = $_POST["kontaktperson"];
    $kontaktpersonTlf = $_POST["kontaktpersonTlf"];
    $kommentar = $_POST["kommentar"];

    // Mappe for opplastede filer
    $uploadDir = "Uploads/";

    // Behandler bildeopplasting hvis aktuelt
    $bildePath = null;
    if (isset($_FILES["bilde"]) && $_FILES["bilde"]["error"] === UPLOAD_ERR_OK) {
        $bildeName = uniqid() . "_" . basename($_FILES["bilde"]["name"]);
        $bildePath = $uploadDir . $bildeName;
        move_uploaded_file($_FILES["bilde"]["tmp_name"], $bildePath);
    }

    // Behandler PDF-opplasting hvis aktuelt
    $pdfPath = null;
    if (isset($_FILES["pdf"]) && $_FILES["pdf"]["error"] === UPLOAD_ERR_OK) {
        $pdfName = uniqid() . "_" . basename($_FILES["pdf"]["name"]);
        $pdfPath = $uploadDir . $pdfName;
        move_uploaded_file($_FILES["pdf"]["tmp_name"], $pdfPath);
    }

    // Setter opp SQL-spørring med parametre
    $sql = "INSERT INTO bedriftskunde (
                orgnr, bedriftsnavn, adresse1, adresse2, postnr, sted,
                epost, kontaktperson, kontaktpersonTlf, kommentar, bilde, pdf
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    // Utfører spørringen med tilhørende verdier
    if ($stmt->execute([
        $orgnr, $bedriftsnavn, $adresse1, $adresse2, $postnr, $sted,
        $epost, $kontaktperson, $kontaktpersonTlf, $kommentar,
        $bildePath, $pdfPath
    ])) {
        // Går tilbake til oversikten ved suksess
        header("Location: ../liste_bedriftkunde/bedriftkunde_liste.php");
        exit();
    } else {
        echo "Feil ved registrering av bedriftskunde.";
    }
} else {
    echo "Ugyldig forespørsel.";
}
?>
