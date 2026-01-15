<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
require_once __DIR__ . '/../../Modele/DAO/MatchDao.php';
require_once __DIR__ . '/../../Modele/Match.php';
requireAuth();

// Compute application base (first path segment) for reliable redirects (e.g. /SENTAYEHU_HISABU_PHP)
$script = str_replace('\\','/', $_SERVER['SCRIPT_NAME'] ?? '');
$parts = explode('/', trim($script, '/'));
$base = '/' . ($parts[0] ?? '');

$pdo = getDBConnection();
$matchDao = new MatchDao($pdo);
$match = null;
$error = '';
$success = '';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $match = $matchDao->getById((int)$id);
    } catch (Exception $e) {
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
            $resultat = '';
            if ($scoreNous !== '' && $scoreAdverse !== '') {
                $sN = (int)$scoreNous;
                $sA = (int)$scoreAdverse;
                $resultat = $sN . '-' . $sA;
            }

            if ($id) {
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
                // Redirect to reload fresh data from DB (Post-Redirect-Get)
                header('Location: ' . $base . '/Vue/Ajouter/ajouter_match.php?id=' . $id . '&success=modified');
                exit;
            } else {
                // Ajout
                $matchObj = new Match_(
                    0, // ID temporaire
                    $dateRencontre,
                    $heure,
                    $nomEquipeAdverse,
                    $lieu,
                    $resultat
                );
                $matchDao->add($matchObj);
                // Redirection automatique vers la liste des matchs
                header('Location: ' . $base . '/Vue/Afficher/afficher_match.php');
                exit;
            }
        } catch (Exception $e) {
            $error = 'Erreur lors de l\'enregistrement: ' . $e->getMessage();
        }
    }
}

// For template display - convert object to array if needed
$match_display = null;
if ($match) {
    $match_display = [
        'Id_Match' => $match->getIdMatch(),
        'Nom_Equipe_Adverse' => $match->getNomEquipeAdverse(),
        'Date_Rencontre' => $match->getDateRencontre(),
        'Heure' => $match->getHeure(),
        'Lieu' => $match->getLieu(),
        'Resultat' => $match->getResultat()
    ];
}
?>
