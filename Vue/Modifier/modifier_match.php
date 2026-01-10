<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
requireAuth();

// Compute project root for redirects
$projectRoot = dirname($_SERVER['SCRIPT_NAME'], 2);

$pdo = getDBConnection();
$id = $_GET['id'] ?? null;

if ($id) {
    header('Location: ' . $projectRoot . '/Vue/Ajouter/ajouter_match.php?id=' . $id);
} else {
    header('Location: ' . $projectRoot . '/Vue/Ajouter/ajouter_match.php');
}
exit;
?>
