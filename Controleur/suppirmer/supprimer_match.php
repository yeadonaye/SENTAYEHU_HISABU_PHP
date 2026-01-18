<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
require_once __DIR__ . '/../../Modele/DAO/MatchDao.php';
requireAuth();

// Base absolue fixe pour les redirections
$base = '/SENTAYEHU_HISABU_PHP';

$pdo = getDBConnection();
$matchDao = new MatchDao($pdo);
$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $match = $matchDao->getById((int)$id);
        if ($match) {
            $matchDao->delete($match);
        }
    } catch (Exception $e) {
        // Ignorer l'erreur
    }
}

header('Location: ' . $base . '/Vue/Afficher/afficher_match.php');
exit;
?>
