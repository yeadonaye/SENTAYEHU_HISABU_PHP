<?php

    require_once __DIR__ . "/../Match.php";
    require_once "ModeleDao.php";

    class MatchDao implements ModeleDao {

        private PDO $pdo;

        public function __construct(PDO $pdo) {
            $this->pdo= $pdo;
        }

        public function getAll(): array{
            $sql = "SELECT * FROM `Match_` ORDER BY Date_Rencontre DESC, Heure DESC";
            $stmt = $this->pdo->query($sql);
            
            $matchs = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $matchs[] = new Match_(
                    $row['Id_Match'],
                    $row['Date_Rencontre'],
                    $row['Heure'],
                    $row['Nom_Equipe_Adverse'],
                    $row['Lieu'],
                    $row['Resultat'] ?? ''
                );
            }
            return $matchs;
        }

        public function getById(int $id): Match_{
            $sql = "SELECT * FROM `Match_` WHERE Id_Match = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$row) return null;
            
            return new Match_(
                $row['Id_Match'],
                $row['Date_Rencontre'],
                $row['Heure'],
                $row['Nom_Equipe_Adverse'],
                $row['Lieu'],
                $row['Resultat'] ?? ''
            );
        }

        public function add(object $obj): bool{
            if (!($obj instanceof Match_)) return false;
            
            $sql = "INSERT INTO `Match_` (Nom_Equipe_Adverse, Date_Rencontre, Heure, Lieu, Resultat) 
                    VALUES (:nom, :date, :heure, :lieu, :resultat)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':nom' => $obj->getNomEquipeAdverse(),
                ':date' => $obj->getDateRencontre(),
                ':heure' => $obj->getHeure(),
                ':lieu' => $obj->getLieu(),
                ':resultat' => $obj->getResultat()
            ]);
        }

        public function update(object $obj): bool{
            if (!($obj instanceof Match_)) return false;
            
            $sql = "UPDATE `Match_` 
                    SET Nom_Equipe_Adverse = :nom,
                        Date_Rencontre = :date,
                        Heure = :heure,
                        Lieu = :lieu,
                        Resultat = :resultat
                    WHERE Id_Match = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':nom' => $obj->getNomEquipeAdverse(),
                ':date' => $obj->getDateRencontre(),
                ':heure' => $obj->getHeure(),
                ':lieu' => $obj->getLieu(),
                ':resultat' => $obj->getResultat(),
                ':id' => $obj->getIdMatch()
            ]);
        }

        public function delete(object $obj): bool{
            if (!($obj instanceof Match_)) return false;
            $sql = "DELETE FROM `Match_` WHERE Id_Match = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $obj->getIdMatch()]);
        }

        public function getCompositionsByMatchId(int $matchId): array{
            $sql = "SELECT Titulaire_ou_pas FROM Participer WHERE Id_Match = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $matchId]);
            
            $participations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $titulaires = 0;
            $remplacants = 0;
            foreach ($participations as $p) {
                if ($p['Titulaire_ou_pas']) {
                    $titulaires++;
                } else {
                    $remplacants++;
                }
            }
            
            return ['titulaires' => $titulaires, 'remplacants' => $remplacants];
        }
    }
?>