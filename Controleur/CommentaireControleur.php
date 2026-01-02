<?php

require_once 'Modele/DAO/CommentaireDao.php';
require_once 'Modele/DAO/JoueurDao.php';

class CommentaireController {

    public function afficher($joueurId) {
        $dao = new CommentaireDao();
        $commentaires = $dao->selectById($joueurId);
        require 'Vue/Afficher/-----.php';
    }

    public function creer($joueurId) {
        require 'Vue/commentaire/ajouter.php';
    }

    public function sauvegarderCreation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentaires = new Commentaire(
                null,
                $_POST['description'],
                $_POST['date_'],
                $_POST['idJoueur']
            );
            $dao = new CommentaireDao();
            $dao->insert($commentaires);
            header("Location: index.php?controller=commentaire&action=index&id=" . $_POST['idJoueur']);
            exit;
        }
    }

    public function modifier($id) {
        $dao = new CommentaireDao();
        $commentaire = $dao->selectById($id);
        require 'Vue/Modifier/modifierCommentaire.php';
    }

    public function sauvegarderModification($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dao = new CommentaireDao();

            $commentaire = new Commentaire(
                $id,
                $_POST['description'],
                $_POST['date_'],
                $_POST['idJoueur']
            );

            $dao->update($commentaire);
            header("Location: index.php?controller=commentaire&action=index&id=" . $_POST['idJoueur']);
            exit;
        }
    }

    public function delete($id) {
        $dao = new CommentaireDao();
        $dao->delete($id);
        header("Location: index.php?controller=commentaire&action=index&id=" . $_POST['idJoueur']);
    }
}
