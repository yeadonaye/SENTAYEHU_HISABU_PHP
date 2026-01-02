<?php

require_once 'Modele/Joueur.php';
require_once 'Modele/DAO/JoueurDao.php';

class JoueurController {

    public function afficher() {
        $dao = new JoueurDao();
        $joueurs = $dao->selectAll();
        require 'Vue/Afficher/afficherJoueurs.php';
    }

    public function creer() {
        require 'Vue/Ajouter/ajouterJoueur.php';
    }

    public function sauvegarderCreation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $joueur = new Joueur(
                null,
                $_POST['num_licence'],
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['date_naissance'],
                $_POST['taille'],
                $_POST['poids'],
                $_POST['statut']
            );
            $dao = new JoueurDao();
            $dao->insert($joueur);
            header("Location: index.php?controller=joueur&action=index");
            exit;
        }
    }

    public function modifier($id) {
        $dao = new JoueurDao();
        $joueur = $dao->selectById($id);
        require 'Vue/Modifier/modifierJoueur.php';
    }

    public function sauvegarderModification($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dao = new JoueurDao();
    
            $joueur = new Joueur(
                $id,
                $_POST['num_licence'],
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['date_naissance'],
                $_POST['taille'],
                $_POST['poids'],
                $_POST['statut']
            );

            $dao->update($joueur);
            header("Location: index.php?controller=joueur&action=index");
            exit;
        }
    }

    public function delete($id) {
        $dao = new JoueurDao();
        $dao->delete($id);
        header("Location: index.php?controller=joueur&action=index");
    }
}
