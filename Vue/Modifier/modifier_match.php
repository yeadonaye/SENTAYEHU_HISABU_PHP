<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
requireAuth();

$pdo = getDBConnection();
$id = $_GET['id'] ?? null;

if ($id) {
    header('Location: /Vue/Ajouter/ajouter_match.php?id=' . $id);
} else {
    header('Location: /Vue/Ajouter/ajouter_match.php');
}
exit;
?>

/* yeahhhhhh buddy */