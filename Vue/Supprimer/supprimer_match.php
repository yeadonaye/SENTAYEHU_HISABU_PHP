<?php
session_start();
require_once '../../routeClient.php';

// Redirection si utilisateur non connecté
if (!isset($_SESSION['token'])) {
    header('Location: ../../login.php');
    exit;
}

$token = $_SESSION['token'];

// Vérification du token via l'API
$verify = routeClient::verifyToken($token);
if ($verify['status_code'] === 401) {
    session_destroy();
    header('Location: ../../login.php');
    exit;
}

// Détermination du rôle utilisateur (API > session > joueur par défaut)
$role = $verify['data']['role'] ?? $_SESSION['role'] ?? 'joueur';

// Récupération de l'ID du match à supprimer
$id = (int)($_GET['id'] ?? 0);

// Suppression du match via l'API si ID valide
if ($id) {
    routeClient::deleteMatch($id, $_SESSION['token']);
}

// Redirection vers la liste des matchs après suppression
header('Location: ../Afficher/afficher_match.php');
exit;
?>