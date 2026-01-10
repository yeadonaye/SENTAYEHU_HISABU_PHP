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
    <!-- Navbar -->
    <?php include '../partials/navbar.php'; ?>

    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #C8102E 0%, #E8283C 100%); color: white; padding: 2rem 0; margin-bottom: 2rem;">
        <div class="container-fluid">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="font-size: 2rem; font-weight: 700; margin: 0;">
                        <i class="bi <?php echo $id ? 'bi-pencil' : 'bi-plus-circle'; ?>"></i> 
                        <?php echo $id ? 'Modifier un Joueur' : 'Ajouter un Joueur'; ?>
                    </h1>
                </div>
                <a href="<?php echo $base; ?>/Vue/Afficher/liste_joueurs.php" class="btn btn-light" style="font-weight: 600;">
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
                    <label for="taille" class="form-label fw-bold">Taille (cm)</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="taille" 
                        name="taille" 
                        value="<?php echo htmlspecialchars($joueur['Taille'] ?? ''); ?>"
                        min="1"
                        placeholder="Ex: 180"
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

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; font-weight: 600; padding: 0.75rem;">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php echo $id ? 'Modifier' : 'Ajouter'; ?>
                    </button>
                    <a href="<?php echo $base; ?>/Vue/Afficher/liste_joueurs.php" class="btn btn-secondary" style="flex: 1; font-weight: 600; padding: 0.75rem; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="footer-content">
            <div style="text-align: center;">
                <h5 class="footer-title">Gestion des Joueurs</h5>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> Gestion des Joueurs. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        // Client-side validation for immediate feedback
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                const numLicence = document.getElementById('numLicence').value.trim();
                const statut = document.getElementById('statut').value.trim();
                const taille = document.getElementById('taille').value.trim();
                const poids = document.getElementById('poids').value.trim();
                if (statut === '') {
                    e.preventDefault();
                    alert('Le statut est obligatoire.');
                    return false;
                }
                
                if (taille !== '') {
                    const t = parseFloat(taille);
                    if (isNaN(t) || t <= 0) {
                        e.preventDefault();
                        alert('La taille doit être un nombre positif.');
                        return false;
                    }
                }
                
                if (poids !== '') {
                    const p = parseFloat(poids);
                    if (isNaN(p) || p <= 0) {
                        e.preventDefault();
                        alert('Le poids doit être un nombre positif.');
                        return false;
                    }
                }
                
                return true;
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
