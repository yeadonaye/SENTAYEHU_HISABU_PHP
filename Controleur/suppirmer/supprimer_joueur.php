<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
requireAuth();

// Compute application base (first path segment) for reliable redirects
$script = str_replace('\\','/', $_SERVER['SCRIPT_NAME'] ?? '');
$parts = explode('/', trim($script, '/'));
$base = '/' . ($parts[0] ?? '');

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

header('Location: ' . $base . '/Vue/Afficher/liste_joueurs.php');
exit;
?>
