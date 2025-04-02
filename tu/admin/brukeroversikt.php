<?php
include("../db2.php"); // Inkluderer tilkoblingen til MySQL-databasen

// Start session og sjekk om brukeren er logget inn som admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("❌ Ikke logget inn. Vennligst logg inn først.");
}

// Sjekk om brukeren er admin
$username = $_SESSION['db_username'];
$isAdmin = false;

// Hent brukerens rettigheter med SHOW GRANTS
try {
    $sql = sprintf("SHOW GRANTS FOR '%s'", $username);
    $stmt = $pdo->query($sql);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $grantString = implode(" ", $row);
        // Sjekk om brukeren er admin
        if (stripos($grantString, "`adminbruker`") !== false) {
            $isAdmin = true;
            break;
        }
    }

    if (!$isAdmin) {
        die("❌ Du har ikke tilstrekkelige rettigheter for å se denne siden.");
    }

} catch (PDOException $e) {
    echo "Feil ved henting av brukerrettigheter: " . $e->getMessage();
}

// Hent brukere fra user_details-tabellen med spesifikke felter
try {
    $sql = "SELECT fornavn, etternavn, user AS brukernavn, telefon FROM user_details"; // Hent spesifikke data
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Feil ved henting av brukerinformasjon: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brukeroversikt</title>
    <link rel="stylesheet" href="../css/liste.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_drop_down" />
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

    </style> 

</head>
<body>
<div id="header">
    <?php include("../header/header.php"); ?>
</div>

<div class="headline-container">
        <h1 class="text-3xl font-light headline-left">Brukeroversikt</h1>
    
      <div class="button-container">
        <a href="#" onclick="redirectToPage('admin/registrer_bruker(admin).php')"><button class="pluss-btn">➕</button></a>
      </div>
</div>

<div class="container">
<?php if ($isAdmin): ?>
    <table>
        <thead>
            <tr>
                <th>Brukernavn</th>
                <th>Fornavn</th>
                <th>Etternavn</th>
                <th>Rolle</th>
                <th>Handlinger</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['brukernavn']); ?></td>
                    <td><?php echo htmlspecialchars($user['fornavn']); ?></td>
                    <td><?php echo htmlspecialchars($user['etternavn']); ?></td>
                    <td>Admin eller bruker</td>
                    <td>✏️🗑️</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Du har ikke tilstrekkelige rettigheter til å se brukeroversikten.</p>
<?php endif; ?>
</div>
</body>
</html>
