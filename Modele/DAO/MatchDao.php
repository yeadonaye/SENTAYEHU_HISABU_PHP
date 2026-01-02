<?php

    require_once "Modele/Match.php";
    require_once "Modele/ModeleDao.php";

    class MatchDao implements ModeleDao{

        private PDO $pdo;

        public function __construct(PDO $pdo) {
            $this->pdo= $pdo;
        }


        public function selectAll(): array{
            $sql = "SELECT * FROM Match ORDER BY dateRencontre";
            $stmt = $this->pdo->query($sql);

            $matches = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $matches[] = new Match(
                    $row['idMatch'],
                    $row['dateRencontre'],
                    $row['heure'],
                    $row['nomEquipeAdverse'],
                    $row['lieu'],
                    $row['resultat']
                );
            }
            return $matches;
        }
        
        public function selectById(int $id): ?Match{
            $sql = "SELECT * FROM Match where idMatch = :id ";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row =$stmt->fetch(PDO::FETCH_ASSOC);

            if(!$row) return null;

            return new Match(
                $row['idMatch'],
                $row['dateRencontre'],
                $row['heure'],
                $row['nomEquipeAdverse'],
                $row['lieu'],
                $row['resultat']
            );
        }

        public function insert(object $obj):bool{
            $sql = "INSERT INTO Match (idMatch, dateRencontre,heure, nomEquipeAdverse, lieu, resultat) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                    $obj->getIdMatch(),
                    $obj->getDateRencontre(),
                    $obj->getHeure(),
                    $obj->getNomEquipeAdverse(),
                    $obj->getLieu(),
                    $obj->getResultat()
            ]);
        }

        public function update(Match $obj):bool{
            if(!$obj instanceof Match) return false;

            $sql = "UPDATE match SET 
                        idMatch = :idMatch,
                        dateRencontre = :dateRencontre,
                        heure = :heure,
                        nomEquipeAdverse = :nomEquipeAdverse,
                        lieu = :lieu,
                        resultat = :resultat
                    WHERE idMatch = :idMatch";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                ':idMatch' => $obj->getIdMatch(),
                ':dateRencontre' => $obj->getDateRencontre(),
                ':heure' => $obj->getHeure(),
                ':nomEquipeAdverse' => $obj->getNomEquipeAdverse(),
                ':lieu' => $obj->getLieu(),
                ':resultat' => $obj->getResultat()
            ]);

        }

        public function delete(Match $obj):bool{
            $sql = "DELETE FROM match WHERE idMatch = :id";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([':id' => $obj->getIdMatch()]);
        }
    }
?>