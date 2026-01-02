<?php
require_once '../../auth.php';
requireAuth();

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

header('Location: calendrier.php');
exit;
?>
