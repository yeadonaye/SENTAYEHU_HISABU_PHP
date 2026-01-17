<?php
include '../../Controleur/ajouter/ajouter_match.php';
// Les données doivent être injectées par le contrôleur
$base = $base ?? '';
$match = $match_display ?? [];
$id = $match['Id_Match'] ?? ($id ?? null);
$error = $error ?? '';
$success = $success ?? '';
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
        <?php include '../Afficher/navbar.php'; ?>
    <!-- Page Header -->
    <div class="page-header-gradient">
        <div class="container-fluid">
            <div class="page-header-content">
                <div>
                    <h1 class="page-header-title">
                        <i class="bi <?php echo $id ? 'bi-pencil' : 'bi-plus-circle'; ?>"></i> 
                        <?php echo $id ? 'Modifier un Match' : 'Planifier un Match'; ?>
                    </h1>
                </div>
                <a href="<?php echo $base; ?>/Vue/Afficher/afficher_match.php" class="btn btn-light btn-retour">
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

        <div class="form-container">
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
                            type="text" 
                            class="form-control" 
                            id="dateRencontre" 
                            name="dateRencontre" 
                            value="<?php echo htmlspecialchars($match['Date_Rencontre'] ?? ''); ?>"
                            pattern="\d{2}/\d{2}/\d{4}"
                            inputmode="numeric"
                            placeholder="jj/mm/aaaa"
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
                            step="60"
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

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php echo $id ? 'Modifier' : 'Ajouter'; ?>
                    </button>
                    <a href="<?php echo $base; ?>/Vue/Afficher/afficher_match.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <?php include '../Afficher/footer.php'; ?>
    <script>
        function parseDateFr(value) {
            const parts = value.split('/');
            if (parts.length !== 3) return null;
            const [jour, mois, annee] = parts.map(Number);
            if (!jour || !mois || !annee) return null;
            // Date object months are 0-based
            const d = new Date(annee, mois - 1, jour);
            // Validate components to avoid 32/13/etc.
            if (d.getFullYear() !== annee || d.getMonth() !== mois - 1 || d.getDate() !== jour) return null;
            return d;
        }

        function checkMatchDate() {
            const dateInput = document.getElementById('dateRencontre').value;
            const heureInput = document.getElementById('heure').value;
            const scoreNous = document.getElementById('scoreNous');
            const scoreAdverse = document.getElementById('scoreAdverse');
            
            const parsedDate = parseDateFr(dateInput);
            if (!parsedDate || !heureInput) {
                scoreNous.disabled = true;
                scoreAdverse.disabled = true;
                return;
            }

            const [h, m] = heureInput.split(':').map(Number);
            if (Number.isNaN(h) || Number.isNaN(m)) {
                scoreNous.disabled = true;
                scoreAdverse.disabled = true;
                return;
            }
            parsedDate.setHours(h, m, 0, 0);
            const now = new Date();
            
            // Désactiver les scores si le match est dans le futur
            if (parsedDate > now) {
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
