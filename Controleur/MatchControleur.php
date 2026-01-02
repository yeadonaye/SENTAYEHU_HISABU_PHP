<?php

require_once 'Modele/DAO/MatchDao.php';

class MatchController {

    public function afficher() {
        $dao = new MatchDao();
        $matchs = $dao->selectAll();
        require 'Vue/Afficher/afficherMatchs.php';
    }

    public function creer() {
        require 'Vue/Ajouter/ajouterMatch.php';
    }

    public function sauvegarderCreation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $match = new Match(
                null,
                $_POST['date_match'],
                $_POST['heure_match'],
                $_POST['lieu_match'],
                $_POST['equipe1'],
                $_POST['equipe2']
            );
            $dao = new MatchDao();
            $dao->insert($match);
            header("Location: index.php?controller=match&action=index");
            exit;
        }
    }


    public function modifier($id) {
        $dao = new MatchDao();
        $match = $dao->selectById($id);
        require 'Vue/Modifier/modifierMatch.php';
    }

    public function sauvegarderModification($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dao = new MatchDao();

            $match = new Match(
                $id,
                $_POST['date_match'],
                $_POST['heure_match'],
                $_POST['lieu_match'],
                $_POST['equipe1'],
                $_POST['equipe2']
            );
            $dao->update($match);
            header("Location: index.php?controller=match&action=index");
            exit;
        }
    }


    public function delete($id) {
        $dao = new MatchDao();
        $dao->delete($id);
        header("Location: index.php?controller=match&action=index");
    }
}
