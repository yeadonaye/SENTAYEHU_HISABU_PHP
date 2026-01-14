<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
requireAuth();

// Compute application base (first path segment) for reliable redirects
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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Modifier un Match' : 'Planifier un Match'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/common.css">
    <link rel="stylesheet" href="../CSS/matchs.css">
</head>
<body>
    <!-- Navbar -->
    <?php include '../partials/navbar.php'; ?>

    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #C8102E 0%, #E8283C 100%); color: white; padding: 2rem 0; margin-bottom: 2rem;">
        <div class="container-fluid">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="font-size: 2rem; font-weight: 700; margin: 0;">
                        <i class="bi <?php echo $id ? 'bi-pencil' : 'bi-plus-circle'; ?>"></i> 
                        <?php echo $id ? 'Modifier un Match' : 'Planifier un Match'; ?>
                    </h1>
                </div>
                <a href="<?php echo $base; ?>/Vue/Afficher/afficher_match.php" class="btn btn-light" style="font-weight: 600;">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); padding: 2rem; max-width: 600px; margin: 0 auto;">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="nomEquipeAdverse" class="form-label fw-bold">Équipe Adverse *</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="nomEquipeAdverse" 
                        name="nomEquipeAdverse" 
                        value="<?php echo htmlspecialchars($match['Nom_Equipe_Adverse'] ?? ''); ?>"
                        required
                        placeholder="Nom de l'équipe adverse"
                    >
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="dateRencontre" class="form-label fw-bold">Date *</label>
                        <input 
                            type="date" 
                            class="form-control" 
                            id="dateRencontre" 
                            name="dateRencontre" 
                            value="<?php echo htmlspecialchars($match['Date_Rencontre'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="heure" class="form-label fw-bold">Heure *</label>
                        <input 
                            type="time" 
                            class="form-control" 
                            id="heure" 
                            name="heure" 
                            value="<?php echo htmlspecialchars($match['Heure'] ?? ''); ?>"
                            required
                        >
                    </div>
                </div>

                <div class="mb-3">
                    <label for="lieu" class="form-label fw-bold">Lieu</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="lieu" 
                        name="lieu" 
                        value="<?php echo htmlspecialchars($match['Lieu'] ?? ''); ?>"
                        placeholder="Lieu du match"
                    >
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="scoreNous" class="form-label fw-bold">Notre Score</label>
                        <input 
                            type="number" 
                            class="form-control score-input" 
                            id="scoreNous" 
                            name="scoreNous" 
                            value="<?php 
                                $scoreNous = '';
                                if (isset($match['Resultat']) && !empty($match['Resultat'])) {
                                    $scores = explode('-', $match['Resultat']);
                                    $scoreNous = $scores[0] ?? '';
                                }
                                echo htmlspecialchars($scoreNous);
                            ?>"
                            min="0"
                            placeholder="0"
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="scoreAdverse" class="form-label fw-bold">Score Adverse</label>
                        <input 
                            type="number" 
                            class="form-control score-input" 
                            id="scoreAdverse" 
                            name="scoreAdverse" 
                            value="<?php 
                                $scoreAdverse = '';
                                if (isset($match['Resultat']) && !empty($match['Resultat'])) {
                                    $scores = explode('-', $match['Resultat']);
                                    $scoreAdverse = $scores[1] ?? '';
                                }
                                echo htmlspecialchars($scoreAdverse);
                            ?>"
                            min="0"
                            placeholder="0"
                        >
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; font-weight: 600; padding: 0.75rem;">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php echo $id ? 'Modifier' : 'Ajouter'; ?>
                    </button>
                    <a href="<?php echo $base; ?>/Vue/Afficher/afficher_match.php" class="btn btn-secondary" style="flex: 1; font-weight: 600; padding: 0.75rem; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <?php include '../partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function checkMatchDate() {
            const dateInput = document.getElementById('dateRencontre').value;
            const heureInput = document.getElementById('heure').value;
            const scoreNous = document.getElementById('scoreNous');
            const scoreAdverse = document.getElementById('scoreAdverse');
            
            if (!dateInput || !heureInput) {
                scoreNous.disabled = true;
                scoreAdverse.disabled = true;
                return;
            }
            
            // Combiner la date et l'heure pour créer un DateTime
            const matchDateTime = new Date(dateInput + 'T' + heureInput);
            const now = new Date();
            
            // Désactiver les scores si le match est dans le futur
            if (matchDateTime > now) {
                scoreNous.disabled = true;
                scoreAdverse.disabled = true;
                scoreNous.title = 'Les scores ne peuvent être saisis que si le match est terminé';
                scoreAdverse.title = 'Les scores ne peuvent être saisis que si le match est terminé';
            } else {
                scoreNous.disabled = false;
                scoreAdverse.disabled = false;
                scoreNous.title = '';
                scoreAdverse.title = '';
            }
        }
        
        // Vérifier la date au chargement et au changement
        document.addEventListener('DOMContentLoaded', () => {
            checkMatchDate();
            
            document.getElementById('dateRencontre').addEventListener('change', checkMatchDate);
            document.getElementById('heure').addEventListener('change', checkMatchDate);
        });
    </script>
</body>
</html>
