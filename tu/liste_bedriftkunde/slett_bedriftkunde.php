<?php
require_once("../db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    try {
        $stmt = $pdo->prepare("DELETE FROM bedriftskunde WHERE id = :id");
        $stmt->execute([":id" => $id]);
        header("Location: bedriftkunde_liste.php");
        exit;
    } catch (PDOException $e) {
        echo "Feil ved sletting: " . htmlspecialchars($e->getMessage());
    }
}
?>