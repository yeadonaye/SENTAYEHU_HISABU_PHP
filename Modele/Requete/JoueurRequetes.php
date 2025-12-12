<?php

require_once '/../../Modele/DAO/JoueurDao.php';
require_once '/../../Modele/Joueur.php';

class JoueurRequetes {
    private PDO $pdo;
    private JoueurDao $joueurDao;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->joueurDao = new JoueurDao($pdo);
    }


    public function getJoueurById(int $id): Joueur {
        try {
            return $this->joueurDao->getById($id);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération du joueur: " . $e->getMessage());
            return null;
        }
    }

    public function getAllJoueurs(): array {
        try {
            return $this->joueurDao->getAll();
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des joueurs: " . $e->getMessage());
            return [];
        }
    }

    public function ajouterJoueur(Joueur $joueur): bool {
        try {
            return $this->joueurDao->add($joueur);
        } catch (Exception $e) {
            error_log("Erreur lors de l'ajout du joueur: " . $e->getMessage());
            return false;
        }
    }


    public function mettreAJourJoueur(Joueur $joueur): bool {
        try {
            return $this->joueurDao->update($joueur);
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du joueur: " . $e->getMessage());
            return false;
        }
    }


    public function supprimerJoueur(int $idJoueur): bool {
        try {
            $joueur = $this->getJoueurById($idJoueur);
            if ($joueur) {
                return $this->joueurDao->delete($joueur);
            }
            return false;
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression du joueur: " . $e->getMessage());
            return false;
        }
    }

}
