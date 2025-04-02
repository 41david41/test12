<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // MySQL server detaljer
        $mysql_host = '10.196.243.25'; // Bytt ut med riktig IP-adresse
        $mysql_db = 'kunde_tabeller';  // Bytt ut med riktig database
        $mysql_port = 3306;

        try {
            // Testforbindelse for å sjekke om brukerens innlogging er gyldig
            $pdo = new PDO("mysql:host=$mysql_host;port=$mysql_port;dbname=$mysql_db;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Lagre innloggingsinfo i session (men ikke passord i klartekst)
            $_SESSION['loggedin'] = true;
            $_SESSION['db_username'] = $username;
            $_SESSION['db_password'] = $password; // Kan krypteres hvis ønskelig

            header("Location: ../landingpage/landingpage.php");
            exit();

        } catch (PDOException $e) {
    // Vis popup-feilmelding og send brukeren tilbake til login-siden
    echo "<script>
            alert('❌ Feil ved innlogging: " . addslashes($e->getMessage()) . "');
            window.location.href='../login/login.html';
          </script>";
}

    } else {
        echo "⚠️ Vennligst fyll ut både brukernavn og passord!";
    }
}
?>
