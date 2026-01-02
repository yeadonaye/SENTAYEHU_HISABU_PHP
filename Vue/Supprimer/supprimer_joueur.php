<?php
require_once '../../auth.php';
requireAuth();

$pdo = getDBConnection();
$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare('DELETE FROM Joueur WHERE Id_Joueur = ?');
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Ignorer l'erreur
    }
}

header('Location: liste_joueurs.php');
exit;
?>
