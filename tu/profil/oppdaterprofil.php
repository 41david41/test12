<?php
session_start();
include("../db2.php"); // Inkluder database-tilkobling

// Anta at brukernavnet lagres i sesjon etter innlogging
$brukernavn = isset($_SESSION['db_username']) ? $_SESSION['db_username'] : "Ukjent Bruker";

// Hvis skjemaet er sendt, behandles det
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $newUsername = $_POST['username'];

    // Sjekk om gammelt passord er riktig
    $sql = "SELECT authentication_string FROM mysql.user WHERE username = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $_SESSION['db_username']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($dbPassword);
    $stmt->fetch();

    if (!password_verify($oldPassword, $dbPassword)) {
        $errorMessage = "Det gamle passordet er feil.";
    } elseif ($newPassword !== $confirmPassword) {
        $errorMessage = "De nye passordene matcher ikke.";
    } else {
        // Hash nytt passord
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Oppdater brukernavn og passord i databasen
        $sql = "UPDATE mysql.user SET username = ?, password = ? WHERE username = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sss", $newUsername, $hashedPassword, $_SESSION['db_username']);

        if ($stmt->execute()) {
            $_SESSION['db_username'] = $newUsername; // Oppdater brukernavn i sesjon
            $successMessage = "Profilen din er oppdatert!";
        } else {
            $errorMessage = "Det oppstod en feil.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brukerprofil</title>
    <link rel="stylesheet" href="../css/oppdaterprofil.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down,arrow_drop_up" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="../redirectToPage.js"></script>
    <style>
        #header {
          width: 100%;
          margin: 0;
          padding: 0;
      }
      #header header {
          width: 100%;
      }
    </style>
</head>
<body>

    <div id="header">
        <?php include("../header/header.php"); ?>
    </div>

    <div class="profil-container">
        <h2>Oppdater Profil</h2>

        <?php if (isset($errorMessage)): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <div class="success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="profil.php" method="POST">
            <label for="username">Brukernavn:</label>
            <input type="text" id="username" name="username" value="<?php echo $brukernavn; ?>" required>

            <label for="old_password">Gammelt passord:</label>
            <input type="password" id="old_password" name="old_password" required>

            <label for="new_password">Nytt passord:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Bekreft nytt passord:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button class="rød-knapp">Endre passord</button>

        </form>
    </div>

</body>
</html>
