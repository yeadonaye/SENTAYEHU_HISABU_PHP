<?php

require_once '/../../Modele/DAO/ParticiperDao.php';
require_once '/../../Modele/Participer.php';

class ParticiperRequetes{
    private PDO $pdo;
    private ParticiperDao $participerDao;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
        $this->participerDao = new ParticiperDao($pdo);
    }

    public function getParticipationById(int $id): Participer{
        try {
            return $this->participerDao->getById($id);
        }catch(Exception $e){
            error_log("Erreur lors de la récupération de le participation " . $e->getMessage());
            return null;
        }
    }

    public function getAllParticipation(): array{
        try{
            return $this->participerDao->getAll();
        }catch(Exception $e){
            error_log("Erreurs lors de la recuperation des participations " . $e->getMessage());
            return [];
        }
    }

    public function ajouterParticiper(Participer $participer): bool{
        try{
            return $this->participerDao->add($participer);
        }catch(Exception $e){
            error_log("Erreurs lors de l'ajout d'une participation " . $e->getMessage());
            return false;
        }
    }

    public function mettreAJourParticiper(Participer $participer): bool{
        try{
            return $this->participerDao->update($participer);
        }catch(Exception $e){
            error_log("Erreurs lors de la mise a jour de la participation " . $e->getMessage());
            return false;
        }
    }

    public function supprimerParticiper(int $IdParticipation): bool{
        try{
            $IdParticipation = $this->getParticipationById($IdParticipation);
            if ($IdParticipation){
                return $this->participerDao->delete($IdParticipation);
            }
            return false;
        }catch(Exception $e){
            error_log("Erreurs lors de la suppression de la participation " . $e->getMessage());
            return false;
        }
    }
}