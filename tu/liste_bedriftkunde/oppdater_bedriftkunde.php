<?php
// Inkluderer databasekonfigurasjon
require_once("../db.php");

// Sjekker at forespørselen er en POST (dvs. at data er sendt fra et skjema)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_GET['id']; // Henter ID fra URL-parameter

    // Henter data sendt fra skjemaet
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

    // Mappe for opplasting av filer
    $uploadDir = "Uploads/";

    // Behandler bildeopplasting hvis det er sendt med
    $bildePath = null;
    if (isset($_FILES["bilde"]) && $_FILES["bilde"]["error"] === UPLOAD_ERR_OK) {
        $bildeName = uniqid() . "_" . basename($_FILES["bilde"]["name"]);
        $bildePath = $uploadDir . $bildeName;
        move_uploaded_file($_FILES["bilde"]["tmp_name"], $bildePath);
    }

    // Behandler PDF-opplasting hvis det er sendt med
    $pdfPath = null;
    if (isset($_FILES["pdf"]) && $_FILES["pdf"]["error"] === UPLOAD_ERR_OK) {
        $pdfName = uniqid() . "_" . basename($_FILES["pdf"]["name"]);
        $pdfPath = $uploadDir . $pdfName;
        move_uploaded_file($_FILES["pdf"]["tmp_name"], $pdfPath);
    }

    // SQL-spørring for å oppdatere kundeinformasjon
    $sql = "UPDATE bedriftskunde SET 
        orgnr = ?, bedriftsnavn = ?, adresse1 = ?, adresse2 = ?, postnr = ?, sted = ?, epost = ?, kontaktperson = ?, kontaktpersonTlf = ?, kommentar = ?";

    $params = [$orgnr, $bedriftsnavn, $adresse1, $adresse2, $postnr, $sted, $epost, $kontaktperson, $kontaktpersonTlf, $kommentar];

    // Hvis bilde er lastet opp, legg det til i SQL-spørringen og parameterlisten
    if ($bildePath !== null) {
        $sql .= ", bilde = ?";
        $params[] = $bildePath;
    }

    // Hvis PDF er lastet opp, legg det til i SQL-spørringen og parameterlisten
    if ($pdfPath !== null) {
        $sql .= ", pdf = ?";
        $params[] = $pdfPath;
    }

    // Legg til WHERE for å spesifisere hvilken rad som skal oppdateres
    $sql .= " WHERE id = ?";
    $params[] = $id;

    // Utfører oppdateringen
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        // Ved suksess, send brukeren tilbake til oversiktslisten
        header("Location: ../liste_bedriftkunde/bedriftkunde_liste.php");
        exit();
    } else {
        echo "Feil under oppdatering av bedriftskunde.";
    }
} else {
    // Hvis ikke POST, gi beskjed
    echo "Ugyldig forespørsel.";
}
?>
