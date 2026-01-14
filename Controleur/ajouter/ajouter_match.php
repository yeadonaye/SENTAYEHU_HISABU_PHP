<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
requireAuth();

// Compute application base (first path segment) for reliable redirects (e.g. /SENTAYEHU_HISABU_PHP)
$script = str_replace('\\','/', $_SERVER['SCRIPT_NAME'] ?? '');
$parts = explode('/', trim($script, '/'));
$base = '/' . ($parts[0] ?? '');

$pdo = getDBConnection();
$match = [];
$error = '';
$success = '';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM `Match_` WHERE Id_Match = ?');
        $stmt->execute([$id]);
        $match = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = 'Erreur lors du chargement du match';
    }
}

// Show success message after redirect (Post-Redirect-Get)
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'modified') {
        $success = 'Match modifié avec succès!';
    } elseif ($_GET['success'] === 'created') {
        $success = 'Match ajouté avec succès!';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomEquipeAdverse = $_POST['nomEquipeAdverse'] ?? '';
    $dateRencontre = $_POST['dateRencontre'] ?? '';
    $heure = $_POST['heure'] ?? '';
    $lieu = $_POST['lieu'] ?? '';
    $scoreNous = $_POST['scoreNous'] ?? '';
    $scoreAdverse = $_POST['scoreAdverse'] ?? '';

    if (empty($nomEquipeAdverse) || empty($dateRencontre) || empty($heure)) {
        $error = 'Les champs avec * sont obligatoires';
    } else {
        try {
            // Créer le résultat au format "3-2" si les scores sont fournis
            $resultat = null;
            if ($scoreNous !== '' && $scoreAdverse !== '') {
                $sN = (int)$scoreNous;
                $sA = (int)$scoreAdverse;
                $resultat = $sN . '-' . $sA;
            }

            if ($id) {
                // Modification
                $stmt = $pdo->prepare('
                    UPDATE `Match_` 
                    SET Nom_Equipe_Adverse = ?, Date_Rencontre = ?, Heure = ?, Lieu = ?, Resultat = ? 
                    WHERE Id_Match = ?
                ');
                $stmt->execute([$nomEquipeAdverse, $dateRencontre, $heure, $lieu, $resultat, $id]);
                // Redirect to reload fresh data from DB (Post-Redirect-Get)
                header('Location: ' . $base . '/Vue/Ajouter/ajouter_match.php?id=' . $id . '&success=modified');
                exit;
            } else {
                // Ajout
                $stmt = $pdo->prepare('
                    INSERT INTO `Match_` (Nom_Equipe_Adverse, Date_Rencontre, Heure, Lieu, Resultat) 
                    VALUES (?, ?, ?, ?, ?)
                ');
                $stmt->execute([$nomEquipeAdverse, $dateRencontre, $heure, $lieu, $resultat]);
                $id = $pdo->lastInsertId();
                // Redirect to the edit page for the newly created match
                header('Location: ' . $base . '/Vue/Ajouter/ajouter_match.php?id=' . $id . '&success=created');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Erreur lors de l\'enregistrement: ' . $e->getMessage();
        }
    }
}
?>