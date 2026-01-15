<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
require_once __DIR__ . '/../../Modele/DAO/MatchDao.php';
require_once __DIR__ . '/../../Modele/Match.php';
requireAuth();

// Compute application base (first path segment) for reliable redirects
$script = str_replace('\\','/', $_SERVER['SCRIPT_NAME'] ?? '');
$parts = explode('/', trim($script, '/'));
$base = '/' . ($parts[0] ?? '');

$pdo = getDBConnection();
$matchDao = new MatchDao($pdo);
$match = [];
$error = '';
$success = '';

$id = $_GET['id'] ?? null;

if (!$id) {
    $error = 'Aucun match spécifié';
} else {
    try {
        $matchObj = $matchDao->getById((int)$id);
        if ($matchObj) {
            // Convert object to array for template
            $match = [
                'Id_Match' => $matchObj->getIdMatch(),
                'Date_Rencontre' => $matchObj->getDateRencontre(),
                'Heure' => $matchObj->getHeure(),
                'Nom_Equipe_Adverse' => $matchObj->getNomEquipeAdverse(),
                'Lieu' => $matchObj->getLieu(),
                'Resultat' => $matchObj->getResultat()
            ];
        } else {
            $error = 'Match non trouvé';
        }
    } catch (Exception $e) {
        $error = 'Erreur lors du chargement du match';
    }
}

// Show success message after redirect (Post-Redirect-Get)
if (isset($_GET['success']) && $_GET['success'] === 'modified') {
    $success = 'Match modifié avec succès!';
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
            $resultat = '';
            if ($scoreNous !== '' && $scoreAdverse !== '') {
                $sN = (int)$scoreNous;
                $sA = (int)$scoreAdverse;
                $resultat = $sN . '-' . $sA;
            }

            // Modification
            $matchObj = new Match_(
                (int)$id,
                $dateRencontre,
                $heure,
                $nomEquipeAdverse,
                $lieu,
                $resultat
            );
            $matchDao->update($matchObj);
            $success = 'Match modifié avec succès!';
            
            // Update $match array for display
            $match = [
                'Id_Match' => $id,
                'Date_Rencontre' => $dateRencontre,
                'Heure' => $heure,
                'Nom_Equipe_Adverse' => $nomEquipeAdverse,
                'Lieu' => $lieu,
                'Resultat' => $resultat
            ];
        } catch (Exception $e) {
            $error = 'Erreur lors de l\'enregistrement: ' . $e->getMessage();
        }
    }
}
?>
