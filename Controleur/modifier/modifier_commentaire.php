<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
require_once __DIR__ . '/../../Modele/DAO/CommentaireDao.php';
require_once __DIR__ . '/../../Modele/Commentaire.php';
requireAuth();

$pdo = getDBConnection();
$commentaireDao = new CommentaireDao($pdo);

$error = '';
$success = '';
$comment = null;

$commentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($commentId <= 0) {
    header('Location: /SENTAYEHU_HISABU_PHP/Vue/Afficher/liste_joueurs.php');
    exit;
}

try {
    $comment = $commentaireDao->getById($commentId);
    if (!$comment) {
        header('Location: /SENTAYEHU_HISABU_PHP/Vue/Afficher/liste_joueurs.php');
        exit;
    }
} catch (Exception $e) {
    $error = "Erreur lors du chargement du commentaire";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description'] ?? '');
    $dateInput = trim($_POST['date_commentaire'] ?? '');

    if ($description === '') {
        $error = 'Le commentaire est obligatoire';
    }

    // Utiliser la date saisie; si invalide -> erreur
    $dateForDb = null;
    if ($dateInput !== '') {
        $dt = DateTime::createFromFormat('Y-m-d\TH:i', $dateInput) ?: DateTime::createFromFormat('Y-m-d', $dateInput);
        if ($dt) {
            $dateForDb = $dt->format('Y-m-d H:i:s');
        } else {
            $error = 'Date de commentaire invalide';
        }
    } else {
        $dateForDb = $comment ? $comment->getDate() : date('Y-m-d H:i:s');
    }

    if (!$error && $comment) {
        try {
            $comment->setDescription($description);
            $comment->setDate($dateForDb);
            $commentaireDao->update($comment);
            header('Location: /SENTAYEHU_HISABU_PHP/Vue/Afficher/afficher_commentaires.php?id=' . $comment->getIdJoueur() . '&success=1');
            exit;
        } catch (Exception $e) {
            $error = "Erreur lors de la mise à jour du commentaire";
        }
    }
}
