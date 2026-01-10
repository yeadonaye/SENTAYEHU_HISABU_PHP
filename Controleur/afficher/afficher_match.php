<?php
// Copied from Vue/matchs/calendrier.php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
requireAuth();

$pdo = getDBConnection();
$matchs = [];
$error = '';

try {
    $stmt = $pdo->query('
        SELECT * FROM `Match_` 
        ORDER BY Date_Rencontre DESC, Heure DESC
    ');
    $matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Erreur lors du chargement des matchs: ' . $e->getMessage();
}

// Récupérer la composition pour chaque match
$compositions = [];
foreach ($matchs as &$match) {
    try {
        $stmt = $pdo->prepare('
            SELECT Titulaire_ou_pas FROM Participer 
            WHERE Id_Match = :id
        ');
        $stmt->execute([':id' => $match['Id_Match']]);
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
        
        $compositions[$match['Id_Match']] = [
            'titulaires' => $titulaires,
            'remplacants' => $remplacants
        ];
    } catch (PDOException $e) {
        $compositions[$match['Id_Match']] = ['titulaires' => 0, 'remplacants' => 0];
    }
}
?>