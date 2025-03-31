<?php
require_once("../db2.php");

function validateInput($data, $pattern) {
    return preg_match($pattern, $data);
}

function sanitize($data) {
    return htmlspecialchars(trim($data));
}

// Validation patterns
$patterns = [
    "fornavn" => "/^[\p{L}\-]{2,}$/u",
    "etternavn" => "/^[\p{L}\-]{2,}$/u",
    "brukernavn" => "/^[a-zA-Z0-9_-]{3,16}$/",
    "epost" => "/^[^@\s]+@[^@\s]+\.[^@\s]+$/",
    "bekreft_epost" => "/^[^@\s]+@[^@\s]+\.[^@\s]+$/",
    "telefon" => "/^\d{8}$/",
    "passord" => "/^.{6,}$/",
    "bekreft_passord" => "/^.{6,}$/"
];

$errors = [];
$data = [];


// Validate input fields
foreach ($patterns as $key => $pattern) {
    if (!isset($_POST[$key]) || !validateInput($_POST[$key], $pattern)) {
        $errors[] = "$key har ugyldig eller manglende verdi.";
    } else {
        $data[$key] = sanitize($_POST[$key]);
    }
}

// Ensure email and password confirmation match
if ($_POST['epost'] !== $_POST['bekreft_epost']) {
    $errors[] = "E-postadressene er ikke like.";
}
if ($_POST['passord'] !== $_POST['bekreft_passord']) {
    $errors[] = "Passordene er ikke like.";
}

// If there are errors, show alert and exit
if (!empty($errors)) {
    echo "<script>alert('" . implode("\n", $errors) . "'); window.location.href='registrer_bruker(admin).html';</script>";
    exit;
}

// Store password safely
$passord = $_POST['passord'];
$brukernavn = $data['brukernavn'];

try {
    // Start a database transaction
    $pdo->beginTransaction();

    // Check if user already exists in MySQL
    $stmtCheck = $pdo->prepare("SELECT User FROM mysql.user WHERE User = :brukernavn AND Host = 'localhost'");
    $stmtCheck->execute([':brukernavn' => $brukernavn]);
    $userExists = $stmtCheck->fetchColumn();

    if ($userExists) {
        die("<script>alert('Brukeren \"$brukernavn\" finnes allerede.'); window.location.href='../admin/brukeroversikt.php';</script>");
    }

    // Execute query (MySQL doesn't allow placeholders for usernames)
    $stmt_user = $pdo->prepare("CREATE USER '$brukernavn'@'localhost' IDENTIFIED BY :passord");
    $stmt_user->execute([
        ':passord' => $passord
    ]);

    // Insert user details into the database
    $stmt_details = $pdo->prepare("
        INSERT INTO user_details (fornavn, etternavn, user, telefon) 
        VALUES (:fornavn, :etternavn, :brukernavn, :telefon)
    ");
    $stmt_details->execute([
        ':fornavn' => $data['fornavn'],
        ':etternavn' => $data['etternavn'],
        ':brukernavn' => $brukernavn,
        ':telefon' => $data['telefon']
    ]);

    // Commit transaction
    $pdo->commit();

    // Redirect to success page
    header("Location: ../admin/brukeroversikt.php");
    exit;
} catch (PDOException $e) {
    // Rollback if an error occurs
    $pdo->rollBack();
    echo "<script>alert('Databasefeil: " . $e->getMessage() . "'); window.location.href='registrer_bruker(admin).html';</script>";
    exit;
}
?>
