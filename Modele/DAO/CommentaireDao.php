<?php

require_once __DIR__ . "/../Commentaire.php";
require_once "ModeleDao.php";

class CommentaireDao implements ModeleDao {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array {
        $sql = 'SELECT * FROM Commentaire ORDER BY idCommentaire';
        $stmt = $this->pdo->query($sql);

        $commentaires = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $commentaires[] = new Commentaire(
                $row['idCommentaire'],
                $row['description'],
                $row['date_'],
                $row['idJoueur']
            );
        }

        return $commentaires;
    }

    public function getById(int $id): ?Commentaire {
        $sql = "SELECT * FROM Commentaire WHERE idCommentaire = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new Commentaire(
            $row['idCommentaire'],
            $row['description'],
            $row['date_'],
            $row['idJoueur']
        );
    }

    public function getByJoueur(int $id): array {
        $sql = "SELECT * FROM Commentaire WHERE idJoueur = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $commentaires = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $commentaires[] = new Commentaire(
                $row['idCommentaire'],
                $row['description'],
                $row['date_'],
                $row['idJoueur']
            );
        }

        return $commentaires;
    }

    // ✅ FIXED INSERT
    public function add(object $obj): bool {
        if (!$obj instanceof Commentaire) return false;

        $sql = "INSERT INTO Commentaire
                (description, date_, idJoueur)
                VALUES(:description, :date_, :idJoueur)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':description' => $obj->getDescription(),
            ':date_' => $obj->getDate() ?: date('Y-m-d'),
            ':idJoueur' => $obj->getIdJoueur()
        ]);
    }

    // ✅ FIXED UPDATE
    public function update(object $obj): bool {
        if (!$obj instanceof Commentaire) return false;

        $sql = "UPDATE Commentaire SET 
                    description = :description,
                    date_ = :date_,
                    idJoueur = :idJoueur
                WHERE idCommentaire = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':description' => $obj->getDescription(),
            ':date_' => $obj->getDate() ?: date('Y-m-d'),
            ':idJoueur' => $obj->getIdJoueur(),
            ':id' => $obj->getIdCommentaire()
        ]);
    }

    public function delete(object $obj): bool {
        if (!$obj instanceof Commentaire) return false;

        $sql = "DELETE FROM Commentaire WHERE idCommentaire = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $obj->getIdCommentaire()
        ]);
    }
}
