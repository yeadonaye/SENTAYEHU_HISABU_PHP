<?php
require_once 'ModeleDao.php';
require_once 'Participer.php';

class ParticiperDao implements ModeleDao{
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function getAll():array{
        // plus tard
    }

    public function getById(int $id):object{
        //plus tard
    }
    
    public function add(object $obj):bool{
        //plus tard
    }

    public function update(object $obj):bool{
        //plus tard
    }

    public function delete(object $obj):bool{
        //plus tard
    }
}
?>