<?php
require_once 'ModeleDao.php';
require_once 'Joueur.php';

class JoueurDao implements ModeleDao {
    private PDO $pdo;

    public function _construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function getAll(): array{
        //plus tard
    }

    public function getById(int $id): object{
        //plus tard
    }

    public function add(object $obj): bool{
        //plus tard
    }

    public function update(object $obj): bool{
        //plus tard
    }

    public function delete(object $obj): bool{
        //plus tard
    }
}
?>