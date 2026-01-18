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
    header('Location: ' . $base . '/Vue/Ajouter/ajouter_match.php?id=' . $id);
} else {
    header('Location: ' . $base . '/Vue/Ajouter/ajouter_match.php');
}
exit;
?>

/* yeahhhhhh buddy */