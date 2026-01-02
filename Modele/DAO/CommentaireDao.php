<?php

    require_once "Modele/Commentaire.php";
    require_once "Modele/ModeleDao.php";

    class CommentaireDao implements ModeleDao {

        private PDO $pdo;

        public function __construct(PDO $pdo) {
            $this->pdo= $pdo;
        }

        // ---------------------------------
        // SELECT ALL
        // ---------------------------------
        public function selectAll(): array{
            $sql = 'SELECT * FROM commentaire order by idCommentaire';
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


        // ---------------------------------
        // SELECT BY JOUEUR
        // --------------------------------- 
        public function selectById(int $id): array {
            $sql = "SELECT * FROM commentaire WHERE idJoueur = :id";
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


        // ---------------------------------
        // INSERT
        // ---------------------------------
        public function insert(object $obj):bool{
            if (!$obj instanceof Commentaire) return false;

            $sql = "INSERT INTO commentaire
                    (description, date_, idJoueur)
                    VALUES(:description, :date_, :idJoueur)";

            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                ':description' => $obj->getDescription(),
                ':date_' => $obj->getDate(),
                ':idJoueur' => $obj->getIdJoueur()
            ]);
        }

        // ---------------------------------
        // UPDATE
        // ---------------------------------
        public function update(object $obj):bool{
        if (!$obj instanceof Commentaire) return false;

        $sql = "UPDATE commentaire SET 
                    description = :description,
                    date_ = :date_,
                    idJoueur = :idJoueur
                WHERE idJoueur = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':description' => $obj->getDescription(),
            ':date_' => $obj->getDate(),
            ':idJoueur' => $obj->getIdJoueur()
        ]);
        }

        // ---------------------------------
        // DELETE
        // ---------------------------------
        public function delete(int $id): bool {
            $sql = "DELETE FROM commentaire WHERE idCommentaire = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        }

    }
?>