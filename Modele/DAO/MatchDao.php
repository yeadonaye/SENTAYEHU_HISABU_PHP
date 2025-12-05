<?php

    require_once "Modele/Match.php";
    require_once "Modele/ModeleDao.php";

    class MatchDao implements ModeleDao {

        private PDO $pdo;

        public function __construct(PDO $pdo) {
            $this->pdo= $pdo;
        }

        public function getAll(): array{
            // plus tard
        }

        public function getById(int $id):object{
            // plus tard
        }

        public function add(object $obj):bool{
            // plus tard
        }

        public function update(object $obj):bool{
            // plus tard
        }

        public function delete(object $obj):bool{
            // plus tard
        }
    }
?>