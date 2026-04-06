<?php
session_start();
require_once '../../routeClient.php';

if (!isset($_SESSION['token'])) {
    header('Location: ../../login.php');
    exit;
}

$token = $_SESSION['token'];

// Vérification du token auprès de l'API d'auth
$verify = routeClient::verifyToken($token);
if ($verify['status_code'] === 401) {
    session_destroy();
    header('Location: ../../login.php');
    exit;
}

$role = $verify['data']['role'] ?? $_SESSION['role'] ?? 'joueur';

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    routeClient::deleteMatch($id, $_SESSION['token']);
}

header('Location: ../Afficher/afficher_match.php');
exit;
?>