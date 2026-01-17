<?php include '../../Controleur/ajouter/ajouter_joueur.php'; ?>

<?php
// Compute base path for assets (e.g. /SENTAYEHU_HISABU_PHP)
$script = str_replace('\\','/', $_SERVER['SCRIPT_NAME'] ?? '');
$parts = explode('/', trim($script, '/'));
$base = '/' . ($parts[0] ?? '');
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Modifier un Joueur' : 'Ajouter un Joueur'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo $base; ?>/Vue/CSS/common.css">
    <link rel="stylesheet" href="<?php echo $base; ?>/Vue/CSS/joueurs.css">
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
                        <?php echo $id ? 'Modifier un Joueur' : 'Ajouter un Joueur'; ?>
                    </h1>
                </div>
                <a href="<?php echo $base; ?>/Vue/Afficher/liste_joueurs.php" class="btn btn-light btn-retour">
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
                    <label for="idJoueur" class="form-label fw-bold">ID du Joueur</label>
                    <input
                        type="number"
                        class="form-control"
                        id="idJoueur"
                        name="idJoueur"
                        value="<?php echo htmlspecialchars($joueur['Id_Joueur'] ?? ''); ?>"
                        placeholder="<?php echo !$id ? 'Laisser vide pour auto-génération' : ''; ?>"
                        min="1"
                        <?php echo $id ? 'disabled class="input-disabled"' : ''; ?>
                    >
                    <?php if (!$id): ?>
                    <small class="text-muted">Laisser vide pour que l'ID soit généré automatiquement</small>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="numLicence" class="form-label fw-bold">Numéro de Licence *</label>
                    <input
                        type="text"
                        class="form-control"
                        id="numLicence"
                        name="numLicence"
                        value="<?php echo htmlspecialchars($joueur['Num_Licence'] ?? ''); ?>"
                        required
                        placeholder="Entrez le numéro de licence"
                    >
                </div>

                <div class="mb-3">
                    <label for="nom" class="form-label fw-bold">Nom *</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="nom" 
                        name="nom" 
                        value="<?php echo htmlspecialchars($joueur['Nom'] ?? ''); ?>"
                        required
                        placeholder="Entrez le nom"
                    >
                </div>

                <div class="mb-3">
                    <label for="prenom" class="form-label fw-bold">Prénom *</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="prenom" 
                        name="prenom" 
                        value="<?php echo htmlspecialchars($joueur['Prenom'] ?? ''); ?>"
                        required
                        placeholder="Entrez le prénom"
                    >
                </div>

                <div class="mb-3">
                    <label for="dateNaissance" class="form-label fw-bold">Date de Naissance</label>
                    <input 
                        type="date" 
                        class="form-control" 
                        id="dateNaissance" 
                        name="dateNaissance" 
                        value="<?php echo htmlspecialchars($joueur['Date_Naissance'] ?? ''); ?>"
                    >
                </div>

                <div class="mb-3">
                    <label for="taille" class="form-label fw-bold">Taille (m)</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="taille" 
                        name="taille" 
                        value="<?php echo htmlspecialchars($joueur['Taille'] ?? ''); ?>"
                        min="0.5"
                        max="2.5"
                        step="0.01"
                        placeholder="Ex: 1.80"
                    >
                </div>

                <div class="mb-3">
                    <label for="poids" class="form-label fw-bold">Poids (kg)</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="poids" 
                        name="poids" 
                        value="<?php echo htmlspecialchars($joueur['Poids'] ?? ''); ?>"
                        min="1"
                        step="0.1"
                        placeholder="Ex: 75"
                    >
                </div>

                <div class="mb-3">
                    <label for="statut" class="form-label fw-bold">Statut *</label>
                    <select class="form-control" id="statut" name="statut" required>
                        <option value="">Sélectionner un statut</option>
                        <?php foreach ($statuts as $s): ?>
                            <option value="<?php echo htmlspecialchars($s); ?>" <?php echo ($joueur['Statut'] ?? '') === $s ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($s); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php echo $id ? 'Modifier' : 'Ajouter'; ?>
                    </button>
                    <a href="<?php echo $base; ?>/Vue/Afficher/liste_joueurs.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="footer-content">
            <div class="text-center">
                <h5 class="footer-title">Gestion des Joueurs</h5>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> Gestion des Joueurs. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <?php include '../Afficher/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
