<?php

require_once 'Modele/DAO/ParticiperDao.php';
require_once 'Modele/DAO/JoueurDao.php';
require_once 'Modele/DAO/MatchDao.php';

class ParticiperController {

    // Prepare match (select players)
    public function prepare($matchId) {
        $match = MatchDao::getById($matchId);
        $joueurs = JoueurDao::getActifs();
        require 'Vue/participer/preparer.php';
    }

    // Save participation (titulaire / remplaçant)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            ParticiperDao::insertMany($_POST);
            header("Location: index.php?controller=match&action=index");
        }
    }

    // Evaluate players after match
    public function evaluer($matchId) {
        $participants = ParticiperDao::getByMatch($matchId);
        require 'Vue/participer/evaluer.php';
    }

    public function updateNotes($matchId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            ParticiperDao::updateNotes($matchId, $_POST);
            header("Location: index.php?controller=match&action=index");
        }
    }
}
