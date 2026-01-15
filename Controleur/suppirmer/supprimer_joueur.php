<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
require_once __DIR__ . '/../../Modele/DAO/JoueurDao.php';
requireAuth();

// Compute application base (first path segment) for reliable redirects
$script = str_replace('\\','/', $_SERVER['SCRIPT_NAME'] ?? '');
$parts = explode('/', trim($script, '/'));
$base = '/' . ($parts[0] ?? '');

$pdo = getDBConnection();
$joueurDao = new JoueurDao($pdo);
$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $joueur = $joueurDao->getById((int)$id);
        if ($joueur) {
            $joueurDao->delete($joueur);
        }
    } catch (Exception $e) {
        // Ignorer l'erreur
    }
}

header('Location: ' . $base . '/Vue/Afficher/liste_joueurs.php');
exit;
?>
