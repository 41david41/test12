<?php
require_once("../db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_GET['id'];

    $orgnr = $_POST["orgnr"];
    $navn = $_POST["navn"];
    $styreleder = $_POST["styreleder"];
    $adresse1 = $_POST["adresse1"];
    $adresse2 = $_POST["adresse2"];
    $postnr = $_POST["postnr"];
    $sted = $_POST["sted"];
    $epost = $_POST["epost"];
    $telefon = $_POST["telefon"];
    $kontaktperson = $_POST["kontaktperson"];
    $kontaktpersonTlf = $_POST["kontaktpersonTlf"];
    $kommentar = $_POST["kommentar"];

    $uploadDir = "Uploads/";

    $bildePath = null;
    if (isset($_FILES["bilde"]) && $_FILES["bilde"]["error"] === UPLOAD_ERR_OK) {
        $bildeName = uniqid() . "_" . basename($_FILES["bilde"]["name"]);
        $bildePath = $uploadDir . $bildeName;
        move_uploaded_file($_FILES["bilde"]["tmp_name"], $bildePath);
    }

    $pdfPath = null;
    if (isset($_FILES["pdf"]) && $_FILES["pdf"]["error"] === UPLOAD_ERR_OK) {
        $pdfName = uniqid() . "_" . basename($_FILES["pdf"]["name"]);
        $pdfPath = $uploadDir . $pdfName;
        move_uploaded_file($_FILES["pdf"]["tmp_name"], $pdfPath);
    }

    $sql = "UPDATE borettslagkunde SET 
        orgnr = ?, navn = ?, styreleder = ?, adresse1 = ?, adresse2 = ?, 
        postnr = ?, sted = ?, epost = ?, telefon = ?, kontaktperson = ?, kontaktpersonTlf = ?, kommentar = ?";

    $params = [
        $orgnr, $navn, $styreleder, $adresse1, $adresse2,
        $postnr, $sted, $epost, $telefon, $kontaktperson, $kontaktpersonTlf, $kommentar
    ];

    if ($bildePath !== null) {
        $sql .= ", bilde = ?";
        $params[] = $bildePath;
    }

    if ($pdfPath !== null) {
        $sql .= ", pdf = ?";
        $params[] = $pdfPath;
    }

    $sql .= " WHERE id = ?";
    $params[] = $id;

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        header("Location: ../liste_borettslag/borettslag_liste.php");
        exit();
    } else {
        echo "Feil under oppdatering av borettslag.";
    }
} else {
    echo "Ugyldig forespørsel.";
}
?>