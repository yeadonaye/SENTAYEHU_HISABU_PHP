<?php
session_start();
require_once '../../routeClient.php';

if (!isset($_SESSION['token'])) {
    header('Location: ../../login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    routeClient::deleteJoueur($id, $_SESSION['token']);
}

header('Location: ../Afficher/liste_joueurs.php');
exit;
?>