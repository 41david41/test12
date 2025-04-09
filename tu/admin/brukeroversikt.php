<?php
include("brukeroversikt_backend.php"); // Inkluderer session og rollelogikk
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
                <th>Telefon</th> <!-- Ny kolonne for telefon -->
                <th>E-post</th> <!-- Ny kolonne for e-post -->
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
                    <td><?php echo htmlspecialchars($user['telefon']); ?></td> <!-- Vis telefonnummer -->
                    <td><?php echo htmlspecialchars($user['epost']); ?></td> <!-- Vis e-post -->
                    <td><?php echo getUserRole($user['brukernavn'], $pdo); ?></td> <!-- Hent og vis brukerens rolle -->
                    <td>
                        <a href="?delete_user=<?php echo htmlspecialchars($user['brukernavn']); ?>" onclick="return confirm('Er du sikker på at du vil slette denne brukeren?');">🗑️</a>
                        <a href="registrer_bruker(admin).php?brukernavn=<?php echo urlencode($user['brukernavn']); ?>">✏️</a>
                        </td>
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
