<?php
ini_set('display_errors', 0); // Disable error display on screen
ini_set('log_errors', 1); // Enable logging of errors
ini_set('error_log', 'error_log.txt'); // Specify the error log file

// Start session og sjekk om brukeren er logget inn som admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include the database connection
include("../db2.php"); 

// Check if the user is logged in
if (!isset($_SESSION['db_username']) || empty($_SESSION['db_username'])) {
    die("<script>alert('Ingen bruker funnet. Logg inn på nytt.'); window.location.href='../login/login.php';</script>");
}

// Get the username from session
$brukernavn = $_SESSION['db_username'];

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the old, new, and confirmed passwords
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate if new and confirmed passwords match
    if ($newPassword !== $confirmPassword) {
        die("<script>alert('De nye passordene matcher ikke.'); window.history.back();</script>");
    }

    try {
        // Change the password directly without using a transaction
        $sql = "ALTER USER '$brukernavn'@'%' IDENTIFIED BY '$newPassword'";

        // Execute the SQL query to change the password
        $pdo->exec($sql);

        // Force MySQL to refresh privileges
        $pdo->exec("FLUSH PRIVILEGES");

        // Log the success message to track the password update
        error_log("Passord for '$brukernavn' ble oppdatert.");

        echo "<script>alert('Passordet er oppdatert!'); window.location.href='../login/login.html';</script>";

        // Reconnect to the database with the new password
        // Close the old connection
        $pdo = null;

        // Create a new connection using the new password
        include("../db2.php");

        // Log the new connection
        error_log("Ny tilkobling er etablert med det nye passordet.");

        // Redirect to profile page with success message
       
        exit;

    } catch (Exception $e) {
        // Log the error to a file without showing it on screen
        error_log("Feil ved oppdatering av passord: " . $e->getMessage());

        // Show user-friendly alert and redirect back
        echo "<script>alert('Noe gikk galt. Vennligst prøv igjen.'); window.history.back();</script>";
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

    <div class="headline-container">
            <h1 class="text-3xl font-light">Endre passord</h1>
    </div>

    <div class="container">
        <form method="POST">
            <input type="password" id="old_password" name="old_password" placeholder="Gammelt passord" required>

            <input type="password" id="new_password" name="new_password" placeholder="Nytt passord" required>

            <input type="password" id="confirm_password" name="confirm_password" placeholder="Bekreft nytt passord" required>

            <button class="rød-knapp" type="submit">Endre passord</button>
        </form>
    </div>

</body>
</html>
