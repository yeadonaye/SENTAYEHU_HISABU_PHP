<?php
// Copied from Vue/joueurs/liste_joueurs.php
// auth.php lives in Modele/DAO; require it relative to this controller
require_once __DIR__ . '/../../Modele/DAO/auth.php';
requireAuth();

$pdo = getDBConnection();
$joueurs = [];
$error = '';

try {
    $stmt = $pdo->query('SELECT * FROM Joueur ORDER BY Nom ASC');
    $joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Erreur lors du chargement des joueurs: ' . $e->getMessage();
}
?>