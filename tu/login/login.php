<?php
session_start();

// 🔐 Håndter POST-forespørsel (innlogging)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Sjekk at både brukernavn og passord er sendt
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // 🔧 Databaseoppsett (juster etter behov)
        $mysql_host = '10.196.243.25';      // IP-adresse til MySQL-server
        $mysql_db   = 'kunde_tabeller';     // Navn på databasen
        $mysql_port = 3306;

        try {
            // 🔌 Forsøk innlogging med brukerens legitimasjon
            $pdo = new PDO(
                "mysql:host=$mysql_host;port=$mysql_port;dbname=$mysql_db;charset=utf8",
                $username,
                $password
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // ✅ Lagre innloggingsinfo i session
            $_SESSION['loggedin']     = true;
            $_SESSION['db_username']  = $username;
            $_SESSION['db_password']  = $password; // Bør krypteres hvis det skal brukes videre

            // 👉 Send brukeren til landingssiden
            header("Location: ../landingpage/landingpage.php");
            exit();

        } catch (PDOException $e) {
            // ❌ Feil ved innlogging – vis feilmelding og send tilbake til login
            echo "<script>
                    alert('❌ Feil ved innlogging: " . addslashes($e->getMessage()) . "');
                    window.location.href='../login/login.html';
                  </script>";
        }

    } else {
        // ⚠️ Mangler brukernavn eller passord
        echo "⚠️ Vennligst fyll ut både brukernavn og passord!";
    }
}
?>
