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
    if ($description === '') {
        $error = 'Le commentaire est obligatoire';
    }

    if (!$error && $comment) {
        try {
            $comment->setDescription($description);
            // Mettre à jour la date à maintenant pour suivre la modification
            $comment->setDate(date('Y-m-d H:i:s'));
            $commentaireDao->update($comment);
            header('Location: /SENTAYEHU_HISABU_PHP/Vue/Afficher/afficher_commentaires.php?id=' . $comment->getIdJoueur() . '&success=1');
            exit;
        } catch (Exception $e) {
            $error = "Erreur lors de la mise à jour du commentaire";
        }
    }
}
