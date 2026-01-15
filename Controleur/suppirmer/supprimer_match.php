<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
require_once __DIR__ . '/../../Modele/DAO/MatchDao.php';
requireAuth();

// Compute application base (first path segment) for reliable redirects
$script = str_replace('\\','/', $_SERVER['SCRIPT_NAME'] ?? '');
$parts = explode('/', trim($script, '/'));
$base = '/' . ($parts[0] ?? '');

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
