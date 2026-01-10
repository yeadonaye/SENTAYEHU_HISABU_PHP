<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
requireAuth();

// Compute project root for redirects
$projectRoot = dirname($_SERVER['SCRIPT_NAME'], 2);

$pdo = getDBConnection();
$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare('DELETE FROM `Match_` WHERE Id_Match = ?');
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Ignorer l'erreur
    }
}

header('Location: ' . $projectRoot . '/Vue/Afficher/afficher_match.php');
exit;
?>
