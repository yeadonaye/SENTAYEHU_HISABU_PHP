<?php
require_once '../../auth.php';
requireAuth();

$pdo = getDBConnection();
$id = $_GET['id'] ?? null;

if ($id) {
    header('Location: ajouter_match.php?id=' . $id);
} else {
    header('Location: ajouter_match.php');
}
exit;
?>
