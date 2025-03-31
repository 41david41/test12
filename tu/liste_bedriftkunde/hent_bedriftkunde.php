<?php
require_once("../db.php");

try {
    $stmt = $pdo->query("SELECT id, bedriftsnavn, bilde FROM bedriftskunde");
    $bedrift = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<div class="kundeprofil-grid">';
    
    foreach ($bedrift as $row) {
        $bilde = !empty($row["bilde"]) ? htmlspecialchars($row["bilde"]) : 'uploads/standard.png';
        echo '
            <div class="kundeprofil-kort" onclick="visProfil(' . $row['id'] . ')">
             <div class="kort-knapper"> 
                <button class="rediger-kort-btn" onclick="window.location.href=\'../registrer_bedriftkunde/registrer_bedriftkunde.html?id=' . $row['id'] . '\'">✏️</button>    
                <form action="slett_bedriftkunde.php" method="POST" onsubmit="return confirm(\'Er du sikker på at du vil slette denne bedriftskunden?\')">
                    <input type="hidden" name="id" value="' . htmlspecialchars($row["id"]) . '">
                    <button type="submit" class="slett-kort-btn">🗑️</button>
                </form>
            </div>
                <img src="' . $bilde . '" class="kundeprofil-bilde">
                <h2 class="kundeprofil-navn">' . htmlspecialchars($row["bedriftsnavn"]) . '</h2>
            </div>';
    }

    $antall = count($bedrift);
    if ($antall < 3) {
        for ($i = 0; $i < 3 - $antall; $i++) {
            echo '<div class="kundeprofil-kort placeholder-kort"></div>';
        }
    }

    echo '</div>';

} catch (PDOException $e) {
    echo "<p>Databasefeil: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>