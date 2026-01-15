<?php
require_once 'ModeleDao.php';
require_once __DIR__ . '/../Joueur.php';

// Classe DAO contenant toutes la logique de la table Joueur
class JoueurDao implements ModeleDao {

    private PDO $pdo;

    // Contructeur principale
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    // Récupère tous les joueurs de la bd
    public function getAll(): array{
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

    // Récupère un joueur par son id
    public function getById(int $id): ?Joueur{
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

    // Ajoute un joueur à la liste
    public function add(object $obj): bool{
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

    // met à jour un joueur parmi la liste
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

    // supprime un joueur parmi la liste
    public function delete(object $obj): bool{
        if(!$obj instanceof Joueur) return false;
        $sql = "DELETE FROM joueur WHERE idJoueur = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([':id' => $obj->getIdJoueur()]);
    }

    // Recupere un joueur par son numéro de licence
    public function getByNumLicence(string $numLicence): ?Joueur { // ?Joueur pour indiquer aue l'objet peut etre null
        $sql = "SELECT * FROM joueur WHERE numLicence = :numLicence";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':numLicence', $numLicence);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) return null;
        
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

}
?>