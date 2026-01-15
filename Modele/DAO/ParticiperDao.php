<?php
require_once 'ModeleDao.php';
require_once 'Participer.php';

class ParticiperDao implements ModeleDao {

    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM participer");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $participations = [];
        foreach ($rows as $r) {
            $participations[] = new Participer(
                $r["idParticipation"],
                $r["poste"],
                $r["note"],
                (bool)$r["titulaireOuPas"]
            );
        }

        return $participations;
    }

    public function getById(int $id): Participer{
        $stmt = $this->pdo->prepare("SELECT * FROM participer WHERE idParticipation = :id");
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Participer(
            $row["idParticipation"],
            $row["poste"],
            $row["note"],
            (bool)$row["titulaireOuPas"]
        );
    }

    public function add(object $obj): bool {
        if (!($obj instanceof Participer)) return false;

        $sql = "INSERT INTO participer (poste, note, titulaireOuPas)
                VALUES (:poste, :note, :titulaire)";

        return $this->pdo->prepare($sql)->execute([
            ':poste'     => $obj->getPoste(),
            ':note'      => $obj->getNote(),
            ':titulaire' => $obj->getTitulaireOuPas()
        ]);
    }

    public function update(object $obj): bool {
        if (!($obj instanceof Participer)) return false;

        $sql = "UPDATE participer
                SET poste = :poste,
                    note = :note,
                    titulaireOuPas = :titulaire
                WHERE idParticipation = :id";

        return $this->pdo->prepare($sql)->execute([
            ':poste'     => $obj->getPoste(),
            ':note'      => $obj->getNote(),
            ':titulaire' => $obj->getTitulaireOuPas(),
            ':id'        => $obj->getIdParticipation()
        ]);
    }


    public function delete(object $obj): bool {
        if (!($obj instanceof Participer)) return false;
        $sql = "DELETE FROM participer WHERE idParticipation = :id";
        return $this->pdo->prepare($sql)->execute([':id' => $obj->getIdParticipation()]);
    }
}
?>
