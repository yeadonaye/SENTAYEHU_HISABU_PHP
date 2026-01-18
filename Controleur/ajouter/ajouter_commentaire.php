<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
require_once __DIR__ . '/../../Modele/DAO/CommentaireDao.php';
require_once __DIR__ . '/../../Modele/DAO/JoueurDao.php';
require_once __DIR__ . '/../../Modele/Commentaire.php';
require_once __DIR__ . '/../../Modele/Joueur.php';
requireAuth();

$pdo = getDBConnection();
$commentaireDao = new CommentaireDao($pdo);
$joueurDao = new JoueurDao($pdo);

$joueur = null;
$error = '';
$success = '';

$joueurId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($joueurId <= 0) {
    header('Location: /Vue/Afficher/liste_joueurs.php');
    exit;
}

try {
    $joueur = $joueurDao->getById($joueurId);
    if (!$joueur) {
        header('Location: /Vue/Afficher/liste_joueurs.php');
        exit;
    }
} catch (Exception $e) {
    $error = "Erreur lors du chargement du joueur";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description'] ?? '');

    if ($description === '') {
        $error = 'Le commentaire est obligatoire';
    }

    if (!$error) {
        try {
            $comment = new Commentaire(0, $description, date('Y-m-d H:i:s'), $joueurId);
            $commentaireDao->add($comment);
            header('Location: /Vue/Afficher/afficher_commentaires.php?id=' . $joueurId . '&success=1');
            exit;
        } catch (Exception $e) {
            $error = "Erreur lors de l'enregistrement du commentaire";
        }
    }
}
