<?php
// Inkluderer databaseoppsett
require_once("../db.php");

// Sjekker at forespørselen er av typen POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_GET['id']; // Henter ID fra URL

    // Henter verdier fra skjemaet
    $fornavn = $_POST["fornavn"];
    $etternavn = $_POST["etternavn"];
    $epost = $_POST["epost"];
    $telefon = $_POST["telefon"];
    $adresse1 = $_POST["adresse1"];
    $adresse2 = $_POST["adresse2"];
    $postnr = $_POST["postnr"];
    $sted = $_POST["sted"];
    $kommentar = $_POST["kommentar"];

    // Mappe for opplastede filer
    $uploadDir = "liste_privatkunde/Uploads/";

    // Håndtering av bildeopplasting
    $bildePath = null;
    if (isset($_FILES["bilde"]) && $_FILES["bilde"]["error"] === UPLOAD_ERR_OK) {
        $bildeName = uniqid() . "_" . basename($_FILES["bilde"]["name"]);
        $bildePath = $uploadDir . $bildeName;
        move_uploaded_file($_FILES["bilde"]["tmp_name"], $bildePath);
    }

    // Håndtering av PDF-opplasting
    $pdfPath = null;
    if (isset($_FILES["pdf"]) && $_FILES["pdf"]["error"] === UPLOAD_ERR_OK) {
        $pdfName = uniqid() . "_" . basename($_FILES["pdf"]["name"]);
        $pdfPath = $uploadDir . $pdfName;
        move_uploaded_file($_FILES["pdf"]["tmp_name"], $pdfPath);
    }

    // SQL-spørring for å oppdatere kundeinformasjonen
    $sql = "UPDATE privatkunde SET 
        fornavn = ?, etternavn = ?, adresse1 = ?, adresse2 = ?, postnr = ?, sted = ?, telefon = ?, epost = ?, kommentar = ?";

    $params = [$fornavn, $etternavn, $adresse1, $adresse2, $postnr, $sted, $telefon, $epost, $kommentar];

    // Legger til bilde og PDF hvis de ble opplastet
    if ($bildePath !== null) {
        $sql .= ", bilde = ?";
        $params[] = $bildePath;
    }

    if ($pdfPath !== null) {
        $sql .= ", pdf = ?";
        $params[] = $pdfPath;
    }

    // Fullfører spørringen med WHERE for å spesifisere kunde-ID
    $sql .= " WHERE id = ?";
    $params[] = $id;

    // Utfører SQL-spørringen
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        // Ved suksess, videresend tilbake til oversikt
        header("Location: ../liste_privatkunde/privatkunde_liste.php");
        exit();
    } else {
        echo "Feil under oppdatering av privatkunde.";
    }
} else {
    echo "Ugyldig forespørsel.";
}
?>
