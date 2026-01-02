<?php
require_once 'ModeleDao.php';
require_once 'Joueur.php';

class JoueurDao implements ModeleDao {

    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function selectAll(): array{
        $sql = "SELECT * FROM joueur ORDER BY nom";
        $stmt = $this->pdo->query($sql);

        $joueurs = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $joueurs[] = new Joueur(
                $row['idJoueur'],
                $row['numLicence'],
                $row['nom'],
                $row['prenom'],
                $row['dateNaissance'],
                $row['taille'],
                $row['poids'],
                $row['statut']
            );
        }
        return $joueurs;
    }

    public function selectById(int $id): ?Joueur{
        $sql = "SELECT * FROM joueur where idJoueur = :id ";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row =$stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row) return null;

        return new Joueur(
            $row['idJoueur'],
                $row['numLicence'],
                $row['nom'],
                $row['prenom'],
                $row['dateNaissance'],
                $row['taille'],
                $row['poids'],
                $row['statut']
        );
    }

    public function insert(object $obj): bool{
        if (!($obj instanceof Joueur)) return false;
        $sql = "INSERT INTO joueur
                (numLicence, nom, prenom, dateNaissance, taille, poids, statut)
                VALUES (:numLicence, :nom, :prenom, :dateNaissance, :taille, :poids, :statut)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':numLicence' => $obj->getNumLicence(),
            ':nom'  => $obj->getNom(),
            ':prenom' => $obj->getPrenom(),
            ':dateNaissance' => $obj->getDateNaissance(),
            ':taille' => $obj->getTaille(),
            ':poids' => $obj->getPoids(),
            ':statut' => $obj->getStatut()
        ]);
    }

    public function update(object $obj): bool{
        if(!$obj instanceof Joueur) return false;

        $sql = "UPDATE joueur SET 
                    numLicence = :numLicence,
                    nom = :nom,
                    prenom = :prenom,
                    dateNaissance = :dateNaissance,
                    taille = :taille,
                    poids = :poids,
                    statut = :statut
                WHERE idJoueur = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':numLicence' => $obj->getNumLicence(),
            ':nom'        => $obj->getNom(),
            ':prenom'     => $obj->getPrenom(),
            ':dateNaissance' => $obj->getDateNaissance(),
            ':taille'     => $obj->getTaille(),
            ':poids'      => $obj->getPoids(),
            ':statut'     => $obj->getStatut(),
            ':id'         => $obj->getIdJoueur()
        ]);
        
    }

    public function delete(int $id): bool{
        $sql = "DELETE FROM joueur WHERE idJoueur = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
?>