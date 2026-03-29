<?php
session_start();
require_once '../../routeClient.php';

if (!isset($_SESSION['token'])) {
    header('Location: ../../login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    routeClient::deleteMatch($id, $_SESSION['token']);
}

header('Location: ../Afficher/afficher_match.php');
exit;
?>