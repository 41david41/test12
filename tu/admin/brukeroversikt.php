<?php
include("brukeroversikt_backend.php"); // Inkluderer session og rollelogikk
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brukeroversikt</title>

    <!-- Fonter og ikoner -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Egne stilark -->
    <link rel="stylesheet" href="../css/liste.css">

    <!-- JavaScript -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="../redirectToPage.js"></script>
</head>
<body>

<!-- Inkluderer felles header -->
<div id="header">
    <?php include("../header/header.php"); ?>
</div>

<!-- Overskrift og ny bruker-knapp -->
<div class="headline-container">
    <h1 class="text-3xl font-light headline-left">BRUKEROVERSIKT</h1>

    <div class="button-container">
        <a href="#" onclick="redirectToPage('admin/registrer_bruker(admin).php')">
            <button class="secondaryBTN">
                <span class="material-icons pil">add</span>
            </button>
        </a>
    </div>
</div>

<!-- Hovedinnhold: tabell med brukere (kun for admin) -->
<div class="container">
<?php if ($isAdmin): ?>
    <table>
        <thead>
            <tr>
                <th>Brukernavn</th>
                <th>Fornavn</th>
                <th>Etternavn</th>
                <th>Telefon</th>
                <th>E-post</th>
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
                    <td><?php echo htmlspecialchars($user['telefon']); ?></td>
                    <td><?php echo htmlspecialchars($user['epost']); ?></td>
                    <td><?php echo getUserRole($user['brukernavn'], $pdo); ?></td>
                    <td>
                        <!-- Slett og rediger bruker -->
                        <a href="?delete_user=<?php echo htmlspecialchars($user['brukernavn']); ?>" onclick="return confirm('Er du sikker på at du vil slette denne brukeren?');">🗑️</a>
                        <a href="registrer_bruker(admin).php?brukernavn=<?php echo urlencode($user['brukernavn']); ?>">✏️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <!-- Hvis ikke admin, vis melding -->
    <p>Du har ikke tilstrekkelige rettigheter til å se brukeroversikten.</p>
<?php endif; ?>
</div>

</body>
</html>
